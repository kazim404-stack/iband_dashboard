<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as ResetPasswordTrait;


class Admin extends Authenticatable implements CanResetPassword
{
    use HasFactory, Notifiable, ResetPasswordTrait;
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'image',
    ];
    protected $hidden = [
        'password',
        'remember_token'
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    
}
