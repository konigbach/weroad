<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tour
 *
 * @property string $uuid
 * @property string $travel_id
 * @property string $name
 * @property string $starting_date
 * @property string $ending_date
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereEndingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereStartingDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereTravelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tour whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Tour extends Model
{
    use HasFactory;
    use HasUuids;

    protected $guarded = [];

    public function formattedPrice(): int
    {
        return $this->price / 100;
    }
}
