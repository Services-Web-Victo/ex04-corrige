<?php

use Slim\App;

return function (App $app) {

    $app->get('/', \App\Action\HomeAction::class)->setName('home');

    // Documentation de l'api
    $app->get('/docs', \App\Action\Docs\SwaggerUiAction::class);

    // Films
    $app->get('/film', \App\Action\Movie\MovieViewAction::class);
    $app->get('/film/{id}', \App\Action\Movie\MovieViewByIdAction::class);
    $app->post('/film', \App\Action\Movie\MovieCreateAction::class);
    $app->put('/film/{id}', \App\Action\Movie\MovieUpdateAction::class);
    $app->delete('/film/{id}', \App\Action\Movie\MovieDeleteAction::class);

};

