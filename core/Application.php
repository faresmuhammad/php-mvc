<?php

namespace Core;

use Core\Database\Database;

class Application
{

    public static string $BASE_DIR;
    public static Application $app;
    public Router $router;
    public Request $request;

    public Database $db;



    public function __construct(string $baseDirectory, array $config)
    {
        self::$BASE_DIR = $baseDirectory;
        self::$app = $this;
        $this->request = new Request();
        $this->router = new Router($this->request);
        $this->db = new Database($config['db']);
    }


    public function run()
    {
        $this->router->resolve();
    }
}