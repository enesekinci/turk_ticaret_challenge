<?php

namespace Core;

use App\Controllers\ErrorController;

final class Request
{
    private static ?Request $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): Request
    {
        if (self::$instance === null) {
            self::$instance = new Request();
        }

        return self::$instance;
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getPath(): string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        $position = strpos($path, '?');

        if ($position === false) {
            return $path;
        }

        return substr($path, 0, $position);
    }

    public function getBody(): array
    {
        $body = [];

        $contentType = $this->getHeader('HTTP_CONTENT_TYPE');

        if ($contentType === 'application/json') {
            $body = json_decode(file_get_contents('php://input'), true);
        }

        if ($contentType === 'application/x-www-form-urlencoded') {

            if ($this->getMethod() === 'post') {
                foreach ($_POST as $key => $value) {
                    $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }

            if ($this->getMethod() === 'get') {
                foreach ($_GET as $key => $value) {
                    $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
                }
            }
        }

        return $body;
    }

    public function getFiles(): array
    {
        $files = [];

        foreach ($_FILES as $key => $value) {
            $files[$key] = $value;
        }

        return $files;
    }

    public function getHeaders(): array
    {
        $headers = [];

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headers[$key] = $value;
            }
        }

        return $headers;
    }

    public function getHeader(string $name): ?string
    {
        $headers = $this->getHeaders();

        return $headers[$name] ?? null;
    }

    public function getBearerToken(): ?string
    {
        $authorizationHeader = $this->getHeader('HTTP_AUTHORIZATION');

        if (!$authorizationHeader) {
            return null;
        }

        $authorizationHeaderParts = explode(' ', $authorizationHeader);

        if (count($authorizationHeaderParts) !== 2) {
            return null;
        }

        $authorizationType = $authorizationHeaderParts[0];
        $authorizationToken = $authorizationHeaderParts[1];

        if ($authorizationType !== 'Bearer') {
            return null;
        }

        return $authorizationToken;
    }

    public function get(?string $key = null, mixed $default = null)
    {
        $body = $this->getBody();

        if (!$key) {
            return $body;
        }

        return $body[$key] ?? $default;
    }

    public function validate($data, $rules)
    {
        return \validate($data, $rules);
    }
}
