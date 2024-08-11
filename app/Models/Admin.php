<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasFactory;
    protected $fillable = [
        'name', 'username', 'phone', 'email', 'password',
    ];

    
    protected $hidden = ['created_at', 'updated_at','password'];
}
