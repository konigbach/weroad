<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTour;

use App\Models\Tour;

final class Controller
{
    public function __invoke(Request $request): Response
    {
        $tour = Tour::create([
            'ending_date' => $request->endingDate(),
            'name' => $request->name(),
            'price' => $request->price(),
            'starting_date' => $request->startingDate(),
            'travel_id' => $request->travel()->id,
        ]);

        return new Response($tour);
    }
}
