<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTour;

use App\Models\Tour;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final readonly class Response implements Responsable
{
    public function __construct(
        private Tour $tour
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'id' => $this->tour->id,
            'travelId' => $this->tour->travel_id,
            'name' => $this->tour->name,
            'startingDate' => $this->tour->starting_date,
            'endingDate' => $this->tour->ending_date,
            'price' => $this->tour->price,
        ], SymfonyResponse::HTTP_CREATED);
    }
}
