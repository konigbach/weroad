<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTour;

use App\Models\Tour;

final class Controller
{
    public function __invoke(Request $request): Response
    {
        $tour = Tour::create([
            'travel_id' => $request->travel()->id,
            'name' => $request->name(),
            'starting_date' => $request->startingDate(),
            'ending_date' => $request->endingDate(),
            'price' => $request->price(),
        ]);

        return new Response($tour);
    }
}
