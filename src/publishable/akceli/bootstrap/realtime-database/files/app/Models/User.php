<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * Database Fields
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property Carbon $email_verified_at
 * @property string $password
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * Relationships
 *
 * @package App\Models
 */
class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected $table = 'users';

    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $fillable = [
        //'id',
        //'name',
        //'email',
        //'email_verified_at',
        //'password',
        //'remember_token',
        //'created_at',
        //'updated_at',
    ];
}
