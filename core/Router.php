<?php

namespace Core;


class Router
{
    private array $routesMap = [];

    public function __construct(private readonly Request $request, private readonly Response $response)
    {
    }

    //initialize request and response in constructor

    //register route

    public function register($method, string $url, callable|array $callback)
    {
        $this->routesMap[$method][$url] = $callback;
        return $this;
    }

    public function routesOf(string $method): array
    {
        return $this->routesMap[$method] ?? [];
    }

    public function routes()
    {
        return $this->routesMap;
    }

    //implement http methods

    public function get(string $url, $callback)
    {
        $this->register("get", $url, $callback);
    }

    public function post(string $url, $callback)
    {
        $this->register("post", $url, $callback);
    }

    public function put(string $url, $callback)
    {
        $this->register("put", $url, $callback);
    }

    public function delete(string $url, $callback)
    {
        $this->register("delete", $url, $callback);
    }

    /**
     * This function is to find the callback that matches the url endpoint
     */
    public function getCallback()
    {
        $method = $this->request->getMethod();
        $url = $this->request->getUrl();
        $url = trim($url, "/");

        // Get all routes for current request method
        $routes = $this->routesOf($method);
        // Start iterating registered routes
        foreach ($routes as $route => $callback) {
            $route = trim($route, "/");
            $paramNames = [];

            if (!$route) {
                continue;
            }

            // Find all param names from route and save in $paramNames
            if (preg_match_all("/\{(\w+)(:[^}]+)?}/", $route, $matches)) {
                $paramNames = $matches[1];
            }

            // Convert route name into regex pattern
            $routeRegex =
                "@^" .
                preg_replace_callback(
                    "/\{\w+(:([^}]+))?}/",
                    fn($m) => isset($m[2]) ? "({$m[2]})" : "(\w+)",
                    $route
                ) .
                '$@';

            // Test and match current route against $routeRegex
            if (preg_match_all($routeRegex, $url, $valueMatches)) {
                $values = [];

                for ($i = 1; $i < count($valueMatches); $i++) {
                    $values[] = $valueMatches[$i][0];
                }

                $params = array_combine($paramNames, $values);
                $this->request->setRouteParams($params);
                return $callback;
            }
        }

        return false;
    }

    //resolve route

    /**
     * @throws \Exception
     */
    public function resolve(string $method = null, string $url = null)
    {
        //get url and method
        $method = $method ?? $this->request->getMethod();
        $url = $url ?? $this->request->getUrl();

        //get the callback of the route
        $callback = $this->routesOf($method)[$url] ?? false;
        if (!$callback) {
            $callback = $this->getCallback();
        }

        //return not found error if there is no callback
        if ($callback === false){
            //todo: return a not found friendly-response
            $this->response->statusCode(404);
            throw new \Exception('No route found');
        }

        //check callback format cases [array, callable]
        //array - means controller method [Controller::class, 'method_name']
        if (is_array($callback)) {
            $callback[0] = new $callback[0];
        }


        //call the callback if it is closure or controller method
        return call_user_func($callback, $this->request,$this->response);
    }

}
