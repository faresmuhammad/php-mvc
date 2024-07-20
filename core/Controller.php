<?php

namespace Core;

class Controller
{

    public static function index(Request $request,Response $response)
    {
        var_dump( $request->id);
/*        return $response->json([
           'status' => 200,
           'user_id' => $request->getRouteParams()['id']
        ]);*/
    }

    public function test()
    {
        echo 'Hello from test controller';
    }
}