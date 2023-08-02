<?php

if (!function_exists('toJson')) {

    function toJson($data)
    {
        if (is_array($data) || is_object($data)) {

            foreach ($data as $key => $value) {

                if (is_array($value)) {
                    $data[$key] = toJson($value);
                } elseif (is_object($value)) {

                    if (is_object($value) && method_exists($value, 'toArray')) {
                        $data[$key] = $value->toArray();
                    } else if (is_object($value) && method_exists($value, '__toString')) {
                        $data[$key] = (string) $value;
                    }
                }
            }
        }

        return $data;
    }
}

if (!function_exists('responseJson')) {
    function responseJson($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');

        echo json_encode(toJson($data), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_PRETTY_PRINT);
        exit;
    }
}
