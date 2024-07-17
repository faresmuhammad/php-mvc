<?php

namespace Core;

class Controller
{

    public static function index(Request $request)
    {
        echo '<pre>';
        var_dump($request->getBody());
        echo '</pre>';
        echo '<br>';
        echo '<pre>';
        var_dump($request->getRouteParams());
        echo '</pre>';
    }

    public function test()
    {
        echo 'Hello from test controller';
    }
}