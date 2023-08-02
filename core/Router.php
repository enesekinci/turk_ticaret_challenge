<?php

namespace Core;

use Core\Route;

class Router
{
    public function getRoutes(): array
    {
        return Route::getRoutes();
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getRequestUri(): string
    {
        $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($requestUri, '?');
        $requestUri = $position === false ? $requestUri : substr($requestUri, 0, $position);
        return $requestUri;
    }

    public function isDefined(?string $method = null, ?string $requestUri = null): bool
    {
        $method = $method ?: $this->getMethod();
        $requestUri = $requestUri ?: $this->getRequestUri();

        return isset($this->getRoutes()[$method][$requestUri]);
    }

    # to go to the controller and run the method

    public function run(?string $method = null, ?string $requestUri = null)
    {
        $method = $method ?: $this->getMethod();
        $requestUri = $requestUri ?: $this->getRequestUri();

        if (!$this->isDefined($method, $requestUri)) {
            return responseJson([
                'message' => 'Route not found',
            ], 404);
        }

        $action = $this->getRoutes()[$method][$requestUri];

        $controller = new $action[0]();

        $controller->{$action[1]}();
    }
}
