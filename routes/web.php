<?php

/**
 * @var \Laravel\Lumen\Routing\Router $router
 */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('{letters}', [
    'as' => 'search',
    'uses' => 'WordsController@allWords'
]);
