<?php

declare(strict_types=1);

namespace Tests\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Assert;

trait WithValidationAssertions
{
    public function setUpWithValidationAssertions(): void
    {
        TestResponse::macro('assertValidationErrorRule', function (string $errorKey) {
            // @phpstan-ignore-next-line
            if ($this->exception === null) {
                Assert::fail('The response didn\'t throw any exception.');
            }

            if (! $this->exception instanceof ValidationException) {
                Assert::fail('The response didn\'t throw a ValidationException.');
            }

            $errors = $this->exception->validator->failed();
            $flatErrors = array_map('strtolower', array_keys(Arr::dot($errors)));

            Assert::assertNotTrue(
                is_null(collect($flatErrors)->first(fn (string $key) => Str::startsWith($key, strtolower($errorKey)))),
                "Key [$errorKey] not found in errors: \n\n ".json_encode(
                    $flatErrors,
                    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT
                )."\n"
            );

            return $this;
        });
    }
}
