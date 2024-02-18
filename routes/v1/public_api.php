<?php

use App\Http\PublicApi\V1\ListTours;
use Illuminate\Routing\Router;

$router->group([
    'prefix' => 'travels',
], function (Router $router) {
    $router->get('{travel:slug}/tours', ListTours\Controller::class);
});
