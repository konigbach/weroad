<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Tour
 *
 * @property string $id
 * @property string $travel_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $starting_date
 * @property \Illuminate\Support\Carbon $ending_date
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Travel $travel
 *
 * @method static \Database\Factories\TourFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereEndingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereStartingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Tour extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];
    protected $casts = [
        'starting_date' => 'datetime',
        'ending_date' => 'datetime',
    ];

    /**
     * @return BelongsTo<Travel, self>
     */
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    /**
     * @return Attribute<int, int>
     */
    public function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100
        );
    }
}
