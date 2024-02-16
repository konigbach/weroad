<?php

use App\Http\PublicApi\V1\ListTours;
use Illuminate\Routing\Router;

$router->group([
    'prefix' => 'tours',
], function (Router $router) {
    $router->get('', ListTours\Controller::class);
});
