<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use ApiPlatform\Metadata\Get;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use ApiPlatform\Metadata\ApiResource;

use Spatie\Permission\Traits\HasRoles;

#[ApiResource(
    operations: [
        new Get(),
    ]
)]
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'pseudo',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'author_id');
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_friend', 'user_id', 'friend_id');
    }
}
