<?php

declare(strict_types=1);

namespace App\ValueObjects;

use InvalidArgumentException;

final readonly class StringAmount
{
    private function __construct(
        public string $amount
    ) {
        $this->validate($amount);
    }

    private function validate(string $amount): void
    {
        if (! is_numeric($amount)) {
            throw new InvalidArgumentException("Value [{$amount}] is not a number.");
        }
    }

    public static function from(int $amount): StringAmount
    {
        return new self((string) $amount);
    }

    public function withZeroNotation(): string
    {
        return (string) ((int) $this->amount * 100);
    }
}
