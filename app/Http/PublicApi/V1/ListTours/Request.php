<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours;

use App\Http\PublicApi\V1\ListTours\DTO\Sort;
use App\ValueObjects\StringAmount;
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
            'dateFrom' => 'nullable|date',
            'dateTo' => 'nullable|date',
            'priceFrom' => 'nullable|numeric',
            'priceTo' => 'nullable|numeric',
            'sortBy' => 'nullable|in:price',
            'sortOrder' => 'required_with:sortBy|in:asc,desc',
        ];
    }

    public function dateFrom(): ?CarbonImmutable
    {
        return $this->nullableDate('dateFrom');
    }

    public function dateTo(): ?CarbonImmutable
    {
        return $this->nullableDate('dateTo');
    }

    public function priceFrom(): ?StringAmount
    {
        return $this->nullableInteger('priceFrom')
            ? StringAmount::from($this->integer('priceFrom'))
            : null;
    }

    public function priceTo(): ?StringAmount
    {
        return $this->nullableInteger('priceTo')
            ? StringAmount::from($this->integer('priceTo'))
            : null;
    }

    public function sort(): ?Sort
    {
        if ($this->string('sortBy')->value() === '') {
            return null;
        }

        return new Sort(
            $this->string('sortBy')->value(),
            $this->string('sortOrder')->value()
        );
    }
}
