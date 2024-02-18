<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTour;

use App\Models\Tour;
use App\Models\Travel;

final class Controller
{
    public function __invoke(Request $request, Travel $travel): Response
    {
        $tour = Tour::create([
            'ending_date' => $request->endingDate(),
            'name' => $request->name(),
            'price' => $request->price(),
            'starting_date' => $request->startingDate(),
            'travel_id' => $travel->id,
        ]);

        return new Response($tour);
    }
}
