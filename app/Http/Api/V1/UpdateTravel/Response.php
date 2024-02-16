<?php

declare(strict_types=1);

namespace App\Http\Api\V1\UpdateTravel;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final readonly class Response implements Responsable
{
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Travel updated successfully',
        ]);
    }
}
