<?php

declare(strict_types=1);

namespace App\Http\Api\V1\UpdateTravel\DTO;

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

    /**
     * @return array{
     *      nature: int,
     *      relax: int,
     *      history: int,
     *      culture: int,
     *      party: int,
     * }
     */
    public function toArray(): array
    {
        return [
            'nature' => $this->nature,
            'relax' => $this->relax,
            'history' => $this->history,
            'culture' => $this->culture,
            'party' => $this->party,
        ];
    }
}
