<?php

namespace Core;

use Core\Database\Database;
use Exception;

class Application
{

    public static string $BASE_DIR;
    public static Application $app;
    public Router $router;
    public Request $request;
    public Response $response;

    public Database $db;


    public function __construct(string $baseDirectory, array $config)
    {
        self::$BASE_DIR = $baseDirectory;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database($config['db']);
    }


    /**
     * @throws Exception
     */
    public function run()
    {
        $this->router->resolve();
    }
}