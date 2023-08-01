<?php

if (!function_exists('response')) {
    function response($data, $statusCode = 200)
    {
        http_response_code($statusCode);

        echo json_encode($data);

        exit;
    }
}
