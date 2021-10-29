<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $position
 * @property string $avatar
 * @property string $role
 * @property string $email_verified_at
 * @property string $password
 * @property string $description
 * @property int $ready_for_work
 * @property boolean $recognized
 * @property string $status
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $withCount = ['comments'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'position'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'social_id',
        'network',
        'role',
        'ip'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function createIfNotExistAndAuth(User|null $user = null, $userFields = []): User
    {
        if ($user === null) {
            $user = static::query()->create($userFields);
        }
        return $user;
    }

    public function comments() {
        return $this->hasMany(Comment::class);
    }

}
