<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTravel;

use App\Models\Travel;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

final readonly class Response implements Responsable
{
    public function __construct(
        private Travel $travel
    ) {
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'id' => $this->travel->id,
        ], SymfonyResponse::HTTP_CREATED);
    }
}
