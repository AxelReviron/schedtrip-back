<?php

namespace App\Models;

use ApiPlatform\Metadata\ApiResource;
use App\Http\Requests\StoreTripRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource(
    rules: StoreTripRequest::class
)]
class Trip extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'label',
        'description',
        'distance',
        'duration',
        'is_public',
        'geojson',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];


    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function stops(): HasMany
    {
        return $this->hasMany(Stop::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_trip');
    }

    public function luggages(): HasMany
    {
        return $this->hasMany(Luggage::class);
    }
}
