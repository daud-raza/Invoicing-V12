<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];


    public function hasRole(string $role): bool
    {
        return $this->role === $role || ($role === 'Viewer' && in_array($this->role, ['Viewer','Accountant','Admin'], true)) || ($role === 'Accountant' && in_array($this->role, ['Accountant','Admin'], true));
    }
}
