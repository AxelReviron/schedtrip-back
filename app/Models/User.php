<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use ApiPlatform\Metadata\Get;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use ApiPlatform\Metadata\ApiResource;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Symfony\Component\Serializer\Attribute\Groups;

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
        'ors_api_key'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'ors_api_key',
        'remember_token',
        'friendsOutgoing',
        'friendsIncoming',
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

    /**
     * Trips of the user.
     *
     * @return HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'author_id');
    }

    /**
     * Friends request received by user in pending status.
     *
     * @return array<string>
     */
    public function getOutgoingFriendsRequestInPendingAttribute(): array
    {
        return $this->friendsOutgoing()
            ->wherePivot('status', 'pending')
            ->get()
            ->map(fn ($user) => "/api/users/{$user->getKey()}")
            ->values()
            ->all();
    }

    /**
     * Friends request sent by user in pending status.
     *
     * @return array<string>
     */
    public function getIncomingFriendsRequestInPendingAttribute(): array
    {
        return $this->friendsIncoming()
            ->wherePivot('status', 'pending')
            ->get()
            ->map(fn ($user) => "/api/users/{$user->getKey()}")
            ->values()
            ->all();
    }

    public function friendsOutgoing(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_friend', 'from_user_id', 'to_user_id')
            ->withPivot('status');
    }

    public function friendsIncoming(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_friend', 'to_user_id', 'from_user_id')
            ->withPivot('status');
    }

    /**
     * Get all friends.
     *
     * @return array<string>
     */
    public function getFriendsAttribute(): array
    {
        $outgoing = $this->friendsOutgoing()->wherePivot('status', 'accepted')->pluck('users.id');
        $incoming = $this->friendsIncoming()->wherePivot('status', 'accepted')->pluck('users.id');
        $ids = $outgoing->merge($incoming)->unique();
        return $ids->map(fn ($id) => "/api/users/$id")->values()->all();
    }

    /**
     * User blocked.
     *
     * @return array<string>
     */
    public function getUsersBlockedAttribute(): array
    {
        return DB::table('user_friend')
            ->where('from_user_id', $this->id)
            ->where('status', 'blocked')
            ->pluck('to_user_id')
            ->map(fn ($id) => "/api/users/$id")
            ->values()
            ->all();
    }

}
