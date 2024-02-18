<?php

use App\Http\PublicApi\V1\ListTours;
use Illuminate\Routing\Router;

$router->group([
    'prefix' => 'travels',
], function (Router $router) {
    $router->get('{slug}', ListTours\Controller::class);
});
