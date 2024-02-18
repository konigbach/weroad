<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours;

use App\Models\Travel;

final readonly class Controller
{
    public function __invoke(Request $request, Travel $travel): Response
    {
        $tours = $travel
            ->tours()
            ->when($request->dateFrom(), function ($query) use ($request) {
                return $query->where('starting_date', '>=', $request->dateFrom());
            })
            ->when($request->dateTo(), function ($query) use ($request) {
                $query->where('starting_date', '<=', $request->dateTo());
            })
            ->when($request->priceFrom(), function ($query) use ($request) {
                $query->where('price', '>=', $request->priceFrom()?->withZeroNotation());
            })
            ->when($request->priceTo(), function ($query) use ($request) {
                $query->where('price', '<=', $request->priceTo()?->withZeroNotation());
            })
            ->when($request->sort(), function ($query) use ($request) {
                /** @phpstan-ignore-next-line */
                $query->orderBy($request->sort()->field, $request->sort()->direction);
            })
            ->orderBy('starting_date', 'asc')
            ->paginate();

        return new Response($tours);
    }
}
