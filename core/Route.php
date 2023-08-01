<?php

namespace Core;

class Route
{
    protected static array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public static function isDefined($method, $requestUri)
    {
        return isset(self::$routes[$method][$requestUri]);
    }

    public static function add(string $method, string $requestUri, array $action): void
    {
        if (!in_array($method, ['GET', 'POST'])) {
            throw new \Exception('Method not allowed');
        }

        if (self::isDefined($method, $requestUri)) {
            throw new \Exception('Route already defined');
        }

        if (count($action) !== 2) {
            throw new \Exception('Action must be an array and must have 2 elements');
        }

        if (!class_exists($action[0])) {
            throw new \Exception('Controller not found');
        }

        if (!method_exists($action[0], $action[1])) {
            throw new \Exception('Method not found');
        }

        self::$routes[$method][$requestUri] = $action;
    }

    public static function get($requestUri, $action): void
    {
        self::add('GET', $requestUri, $action);
    }

    public static function post($requestUri, $action): void
    {
        self::add('POST', $requestUri, $action);
    }

    public static function match(array $methods, $requestUri, $action): void
    {
        foreach ($methods as $method) {
            $method = strtoupper($method);

            if (!in_array($method, ['GET', 'POST'])) {
                throw new \Exception('Method not allowed');
            }

            self::add($method, $requestUri, $action);
        }
    }

    public static function getRoutes(): array
    {
        return self::$routes;
    }
}
