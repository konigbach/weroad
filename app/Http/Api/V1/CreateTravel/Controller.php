<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTravel;

use App\Models\Travel;

final readonly class Controller
{
    public function __invoke(Request $request): Response
    {
        $travel = Travel::create([
            'days' => $request->days(),
            'description' => $request->description(),
            'is_public' => $request->isPublic(),
            'moods' => $request->moods(),
            'name' => $request->name(),
            'slug' => $request->slug(),
        ]);

        return new Response($travel);
    }
}
