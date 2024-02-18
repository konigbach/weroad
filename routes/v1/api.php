<?php

use App\Http\Api\V1\CreateTour;
use App\Http\Api\V1\CreateTravel;
use App\Http\Api\V1\UpdateTravel;
use Illuminate\Routing\Router;

$router->group([
    'prefix' => 'travels',
], function (Router $router) {
    $router->post('', CreateTravel\Controller::class);
    $router->put('{travel}', UpdateTravel\Controller::class);
    $router->post('{slug}/tours', CreateTour\Controller::class);
});
