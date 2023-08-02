<?php

namespace App\Managers;

use ReflectionClass;

final class ErrorCode
{
    const TOKEN_IS_EXPIRED = 1;
    const TOKEN_IS_INVALID = 2;
    const TOKEN_MUST_BE_REFRESH = 3;
    const USER_IS_NOT_AUTHORIZED = 4;
    const TOKEN_MUST_BE_ACCESS = 5;
    const USER_IS_NOT_ACTIVE = 6;

    const SUCCESS_LOGIN = 7;
    const SUCCESS_DELETED = 8;
    const SUCCESS_UPDATED = 9;
    const SUCCESS_CREATED = 10;
    const SUCCESS_SEND = 11;
    const SUCCESS_LOGOUT = 12;
    const MAIL_FAILED = 13;


    const BAD_REQUEST = 400;
    const USER_IS_NOT_AUTHENTICATED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const TOO_MANY_REQUESTS = 429;
    const INTERNAL_SERVER_ERROR = 500;

    # Validation Messages

    const REQUIRED = 1001;
    const MIN = 1002;
    const MAX = 1003;
    const BOOLEAN = 1004;
    const NUMERIC = 1005;
    const TOO_LONG = 1006;
    const ALREADY_TAKEN = 1007;
    const NOT_EXISTS = 1008;
    const INVALID = 1009;
    const NOT_MATCH = 1010;
    const FAILED = 1011;
    const NOT_IN = 1012;
    const DATE_FORMAT = 1013;
    const FILE_TYPE = 1014;
    const FILE_MIMES = 1015;
    const NOT_SELECTED = 1016;
    const DUPLICATE = 1017;
    const STRING = 1018;
    const INTEGER = 1019;
    const ARRAY = 1020;
    const EMAIL = 1021;
    const LENGTH = 1022;
    const DIGITS = 1023;

    public static function errorMessage(int $code)
    {
        return match ($code) {
            1 => 'Token is expired',
            2 => 'Token is invalid',
            3 => 'Invalid token type, must be refresh token',
            4 => 'User is not authorized',
            5 => 'Invalid token type, must be access token',
            6 => 'User is not active',
            7 => 'Login is successful',
            8 => 'Delete is successful',
            9 => 'Update is successful',
            10 => 'Create is successful',
            11 => 'Send is successful',
            12 => 'Logout is successful',
            13 => 'Mail is failed',

            400 => 'Bad request',
            401 => 'User is not authenticated',
            403 => 'Forbidden',
            404 => 'Not found',
            405 => 'Method not allowed',
            429 => 'Too many requests',
            500 => 'Internal server error',


            1001 => 'This field is required',
            1002 => 'This field is not less',
            1003 => 'This field is not greater',
            1004 => 'This field is not boolean',
            1005 => 'This field is not numeric',
            1006 => 'This field is too long',
            1007 => 'This field is already taken',
            1008 => 'This field is not exists',
            1009 => 'This field is invalid',
            1010 => 'This field is not match',
            1011 => 'That action is failed',
            1012 => 'This field is not in',
            1013 => 'This field is not date format',
            1014 => 'This field is not file type',
            1015 => 'This field is invalid file mimes',
            1016 => 'This field is not selected',
            1017 => 'This field is duplicate',
            1018 => 'This field is not string',
            1019 => 'This field is not integer',
            1020 => 'This field is not array',
            1021 => 'This field is not email',
            1022 => 'This field is not length',
            1023 => 'This field is not digits',

                // default => throw new \Exception('Invalid Message Number'),
            default => null,
        };
    }

    public static function errorMessages(): array
    {
        $messages = [];

        $oClass = new ReflectionClass(__CLASS__);
        $constants = $oClass->getConstants();

        foreach ($constants as $key => $value) {
            $messages[$value] = self::errorMessage($value);
        }

        return $messages;
    }
}
