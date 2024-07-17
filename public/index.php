<?php

use Core\Application;
use Core\Controller;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$app = new Application();

/*$app->router->get('/test/{id}/{anything}', function (\Core\Request $request) {
    echo 'Hello World!';
    echo '<pre>';
    var_dump($request->getRouteParams());
    echo '</pre>';
});*/

$app->router->get('/user/{id}/{h}',[Controller::class,'index']);
$app->run();
