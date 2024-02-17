<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTour;

use App\Models\Tour;
use App\Models\Travel;
use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

final class Request extends FormRequest
{
    public function authorize(Gate $gate): Response
    {
        return $gate->inspect('create', Tour::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'ending_date' => 'required|date|after:starting_date',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'starting_date' => 'required|date',
        ];
    }

    public function travel(): Travel
    {
        return Travel::query()
            ->where('slug', $this->routeString('slug'))
            ->firstOrFail();
    }

    public function name(): string
    {
        return $this->string('name')->value();
    }

    public function startingDate(): CarbonImmutable
    {
        return $this->immutableDate('starting_date');

    }

    public function endingDate(): CarbonImmutable
    {
        return $this->immutableDate('ending_date');
    }

    public function price(): int
    {
        return $this->integer('price');
    }
}
