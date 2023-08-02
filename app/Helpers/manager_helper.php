<?php

use App\Models\User;

if (!function_exists('basket')) {
    function basket()
    {
        return \App\Modules\Basket::getInstance();
    }
}

if (!function_exists('user')) {
    function user(): User
    {
        # TODO: oturum açan kullanıcıya göre alınacak. ve her çağrımda db den çekilmeyecek.
        return User::find(1);
    }
}
