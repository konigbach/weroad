<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours;

use App\Models\Tour;

final readonly class Controller
{
    public function __invoke(Request $request): Response
    {
        $tours = Tour::query()
            ->when($request->has('dateFrom'), function ($query) use ($request) {
                return $query->where('starting_date', '>=', $request->dateFrom());
            })
            ->when($request->has('dateTo'), function ($query) use ($request) {
                $query->where('starting_date', '<=', $request->dateTo());
            })
            ->when($request->has('priceFrom'), function ($query) use ($request) {
                $query->where('price_from', '>='.$request->priceFrom());
            })
            ->when($request->has('priceTo'), function ($query) use ($request) {
                $query->where('price_to', '<='.$request->priceTo());
            })
            ->get();

        return new Response($tours);
    }
}
