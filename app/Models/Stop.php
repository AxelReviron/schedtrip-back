<?php

namespace App\Models;

use ApiPlatform\Laravel\Eloquent\Filter\OrderFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\QueryParameter;
use App\Http\Requests\Stop\StoreStopRequest;
use App\Http\Requests\Stop\UpdateStopRequest;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            rules: StoreStopRequest::class
        ),
        new Patch(
            rules: UpdateStopRequest::class
        ),
        new Delete()
    ]
)]
#[QueryParameter(key: 'sort[:property]', filter: OrderFilter::class)]
class Stop extends Model
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
        'trip_id',
        'latitude',
        'longitude',
        'arrival_date',
        'departure_date',
        'order_index'
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
