<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours;

use App\Models\Tour;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

final readonly class Response implements Responsable
{
    /**
     * @param  Collection<int, Tour>  $tours
     */
    public function __construct(
        private Collection $tours
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'tours' => $this->tours->map(fn (Tour $tour) => $tour->toArray())->toArray(),
        ]);
    }
}
