<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository as Config;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class DetectTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function __construct(private Config $config) {
        //
    }

    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $centralDomains = config('tenancy.central_domains');


        if (in_array($host, $centralDomains, true)) {
            return $next($request); // central app (marketing/landing)
        }
        $parts = explode('.', $host);
        if (count($parts) < 3) {
            // e.g. company.example.test → 3 parts; if not, treat as 404
            throw new NotFoundHttpException('Tenant subdomain not found.');
        }
        $subdomain = array_shift($parts);


        $tenant = Cache::remember("tenant:{$subdomain}", 300, function () use ($subdomain) {
            return Tenant::where('subdomain', $subdomain)->first();
        });


        if (! $tenant) {
            throw new NotFoundHttpException('Unknown tenant.');
        }

        $this->config->set("database.connections.tenant", [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'), // or store host per-tenant too
            'port' => env('DB_PORT', '3306'),
            'database' => $tenant->database_name,
            'username' => $tenant->database_user,
            'password' => $tenant->database_password,
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);


        DB::purge('tenant');
        DB::setDefaultConnection('tenant');

        if ($tenant->smtp_host) {
            $this->config->set('mail.default', 'smtp');
            $this->config->set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $tenant->smtp_host,
                'port' => (int)($tenant->smtp_port ?: 587),
                'encryption' => $tenant->smtp_encryption ?: 'tls',
                'username' => $tenant->smtp_user,
                'password' => $tenant->smtp_password,
                'timeout' => null,
                'auth_mode' => null,
            ]);
            if ($tenant->smtp_from_email) {
                $this->config->set('mail.from.address', $tenant->smtp_from_email);
                $this->config->set('mail.from.name', $tenant->smtp_from_name ?: $tenant->company_name);
            }
        }


        // Tag current tenant on the request/container
        app()->instance('currentTenant', $tenant);
        return $next($request);
    }
}
