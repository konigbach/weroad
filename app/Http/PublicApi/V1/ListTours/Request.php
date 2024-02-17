<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours;

use Carbon\CarbonImmutable;
use Illuminate\Auth\Access\Response;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Foundation\Http\FormRequest;

final class Request extends FormRequest
{
    public function authorize(Gate $gate): Response
    {
        return Response::allow();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'dateFrom' => 'nullable|numeric',
            'dateTo' => 'nullable|numeric',
            'priceFrom' => 'nullable|numeric',
            'priceTo' => 'nullable|numeric',
            'slug' => 'nullable|string',
        ];
    }

    public function slug(): string
    {
        return $this->routeString('slug');
    }

    public function priceFrom(): int
    {
        return $this->integer('priceFrom');
    }

    public function priceTo(): int
    {
        return $this->integer('priceTo');
    }

    public function dateFrom(): CarbonImmutable
    {
        return $this->immutableDate('dateFrom');
    }

    public function dateTo(): CarbonImmutable
    {
        return $this->immutableDate('dateTo');
    }
}
