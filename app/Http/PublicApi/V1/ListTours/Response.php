<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours;

use App\Models\Tour;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

final readonly class Response implements Responsable
{
    /**
     * @param  LengthAwarePaginator<Tour>  $tours
     */
    public function __construct(
        private LengthAwarePaginator $tours
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'meta' => [
                'current_page' => $this->tours->currentPage(),
                'from' => $this->tours->firstItem(),
                'last_page' => $this->tours->lastPage(),
                'per_page' => $this->tours->perPage(),
                'to' => $this->tours->lastItem(),
                'total' => $this->tours->total(),
            ],
            'data' => [
                'tours' => $this->tours->map(function (Tour $tour) {
                    return [
                        'id' => $tour->id,
                        'endingDate' => $tour->ending_date->format('Y-m-d'),
                        'name' => $tour->name,
                        'price' => $tour->price,
                        'startingDate' => $tour->starting_date->format('Y-m-d'),
                    ];
                }),
            ],
        ]);
    }
}
