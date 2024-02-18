<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTour;

use App\Models\Tour;
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
            'endingDate' => 'required|date|after:startingDate',
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'startingDate' => 'required|date',
        ];
    }

    public function name(): string
    {
        return $this->string('name')->value();
    }

    public function startingDate(): CarbonImmutable
    {
        return $this->immutableDate('startingDate');

    }

    public function endingDate(): CarbonImmutable
    {
        return $this->immutableDate('endingDate');
    }

    public function price(): int
    {
        return $this->integer('price');
    }
}
