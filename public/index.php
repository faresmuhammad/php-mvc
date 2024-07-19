<?php

use Core\Application;
use Core\Controller;

require_once dirname(__DIR__) . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = [
    'db' => [
        'host' => $_ENV['DB_HOST'],
        'dbname' => $_ENV['DB_NAME'],
        'port' => $_ENV['DB_PORT'],
        'user' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASSWORD'],
    ]
];
$app = new Application(dirname(__DIR__), $config);

/*$app->router->get('/test/{id}/{anything}', function (\Core\Request $request) {
    echo 'Hello World!';
    echo '<pre>';
    var_dump($request->getRouteParams());
    echo '</pre>';
});*/

$app->router->get('/user/{id}/{h}', [Controller::class, 'index']);
$app->run();
