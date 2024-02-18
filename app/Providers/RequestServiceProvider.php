<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class RequestServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerImmutableDate();
        $this->registerNullableDate();
        $this->registerTypeSafeNullablePrimitiveMethods();
    }

    private function registerTypeSafeNullablePrimitiveMethods(): void
    {
        Request::macro('nullableInteger', fn (string $key): ?int => request()?->has($key) ? request()?->integer($key) : null);
    }

    private function registerImmutableDate(): void
    {
        Request::macro('immutableDate', function (string $key): CarbonImmutable {
            $date = request()?->date($key);

            $immutableDate = CarbonImmutable::create($date);

            assert($immutableDate instanceof CarbonImmutable);

            return $immutableDate;
        });
    }

    private function registerNullableDate(): void
    {
        Request::macro('nullableDate', function (string $key): ?CarbonImmutable {
            $date = request()?->date($key);

            if (! $date) {
                return null;
            }

            $date = CarbonImmutable::create($date);

            return $date === false ? null : $date;
        });
    }
}
