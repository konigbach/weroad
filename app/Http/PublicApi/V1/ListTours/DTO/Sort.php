<?php

declare(strict_types=1);

namespace App\Http\PublicApi\V1\ListTours\DTO;

final readonly class Sort
{
    public function __construct(
        public string $field,
        public string $direction,
    ) {
    }
}
