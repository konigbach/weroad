<?php

use App\Http\Api\V1\CreateTour;
use App\Http\Api\V1\CreateTravel;
use App\Http\Api\V1\UpdateTravel;
use Illuminate\Routing\Router;

$router->group([
    'prefix' => 'travels',
], function (Router $router) {
    //$router->get('', UpdateTravel\Controller::class);
    $router->post('', CreateTravel\Controller::class);
    $router->post('{slug}/create', CreateTour\Controller::class);
});
