<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;


    protected $fillable = [
        'company_name',
        'subdomain',
        'database_name',
        'database_user',
        'database_password',
        'smtp_host', 
        'smtp_port', 
        'smtp_user', 
        'smtp_password', 
        'smtp_encryption', 
        'smtp_from_email', 
        'smtp_from_name',
    ];
}
