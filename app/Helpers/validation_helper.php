<?php

if (!function_exists('validator')) {
    function validate(array $data, array $rules): array|bool
    {
        return (new \App\Managers\Validator())->validate($data, $rules);
    }
}
