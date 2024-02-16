<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Travel
 *
 * @property string $uuid
 * @property int $public
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property int $days
 * @property array $moods
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel query()
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereMoods($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel wherePublic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Travel whereUuid($value)
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

    public function nights(): int
    {
        return $this->days - 1;
    }
}
