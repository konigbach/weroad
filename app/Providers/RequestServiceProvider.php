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
        $this->registerRoutePrimitiveMethods();
    }

    private function registerRoutePrimitiveMethods(): void
    {
        Request::macro('routeString', function (string $key): string {
            $value = request()?->route($key);

            assert(is_string($value));

            return $value;
        });
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
}
