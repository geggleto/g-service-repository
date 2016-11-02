<?php

use G\Registry\Action\DeRegisterService;
use G\Registry\Action\RegisterService;
use G\Registry\Action\ServiceQuery;

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__."/../");
$dotenv->load();

$app = new Slim\App();

$container = $app->getContainer();

$container['redis'] = function ($c) {
    $client = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => getenv('REDIS_IP'),
        'port'   => getenv('REDIS_PORT'),
        'password'   => getenv('REDIS_PASSWORD')
    ]);

    return $client;
};

$container[RegisterService::class] = function ($c) {
    return new RegisterService($c['redis']);
};

$container[DeRegisterService::class] = function ($c) {
    return new DeRegisterService($c['redis']);
};

$container[ServiceQuery::class] = function ($c) {
    return new ServiceQuery($c['redis']);
};

$app->get('/service/{name}', ServiceQuery::class);
$app->post('/service', RegisterService::class);
$app->delete('/service', DeRegisterService::class);

$app->run();
