<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Travel
 *
 * @property string $id
 * @property int $is_public
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property int $days
 * @property array $moods
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $nights
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tour> $tours
 * @property-read int|null $tours_count
 *
 * @method static \Database\Factories\TravelFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereIsPublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereMoods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Travel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'travels';
    protected $guarded = [];
    protected $casts = [
        'moods' => 'array',
    ];

    /**
     * @return HasMany<Tour>
     */
    public function tours(): HasMany
    {
        return $this->hasMany(Tour::class);
    }

    /**
     * @return Attribute<int, never>
     */
    public function nights(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => $this->days - 1
        );
    }
}
