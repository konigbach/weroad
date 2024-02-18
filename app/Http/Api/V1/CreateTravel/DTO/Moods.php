<?php

declare(strict_types=1);

namespace App\Http\Api\V1\CreateTravel\DTO;

final readonly class Moods
{
    public function __construct(
        public int $nature,
        public int $relax,
        public int $history,
        public int $culture,
        public int $party,
    ) {
    }
}
