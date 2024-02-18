<?php

declare(strict_types=1);

namespace App\Http\Api\V1\UpdateTravel;

use App\Models\Travel;

final readonly class Controller
{
    public function __invoke(Request $request, Travel $travel): Response
    {
        $travel = Travel::query()
            ->update([
                'slug' => $request->slug(),
                'is_public' => $request->isPublic(),
                'name' => $request->name(),
                'description' => $request->description(),
                'days' => $request->days(),
                'moods' => $request->moods()->toArray(),
            ]);

        return new Response();
    }
}
