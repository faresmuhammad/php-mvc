<?php

namespace Core;

class Response
{

    public function statusCode(int $code)
    {
        http_response_code($code);
        return $this;
    }

    public function redirect(string $url, int $code = 302)
    {
        http_response_code($code);
        header("Location: $url");
    }

    /**
     * @param array $data
     * @return string //json string
     */
    public function json(array $data, int $code = 200): string
    {
        http_response_code($code);
        header('Content-Type: application/json');
        return json_encode($data);
    }
}