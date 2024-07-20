<?php

namespace Core;

/**
 * Request is responsible for encapsulate data within http request [headers, request method, url, route params, body content]
 */
class Request
{

    public function __construct()
    {
        $this->getBody();
    }

    private array $routeParams = [];

    //get request method
    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    //get the url
    public function getUrl(): string
    {
        return explode('?', $_SERVER['REQUEST_URI'])[0] ?? '/';
    }

    public function setRouteParams(array $params)
    {
        $this->routeParams = $params;
        foreach ($this->routeParams as $key => $value) {
            $this->{$key} = $value;
        }
        return $this;
    }

    public function getRouteParams(): array
    {
        return $this->routeParams;
    }

    public function getBody(): array
    {
        $data = [];
        if ($this->getMethod() === 'get') {
            foreach ($_GET as $key => $value) {
                $data[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                $this->{$key} = $value;
            }
        }
        if ($this->getMethod() === 'post') {
            foreach ($_POST as $key => $value) {
                $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                $this->{$key} = $value;
            }
        }
        return $data;
    }


}