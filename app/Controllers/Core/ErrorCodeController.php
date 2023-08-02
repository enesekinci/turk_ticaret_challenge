<?php

namespace App\Controllers\Core;

use App\Controllers\Controller;
use App\Managers\ErrorCode;

class ErrorCodeController extends Controller
{
    public function action()
    {
        $code = \request()->get('code');

        if (!$code) {
            return \responseJson([
                'status' => true,
                'messages' => ErrorCode::errorMessages(),
            ]);
        }

        $message = ErrorCode::errorMessage($code);

        if (\is_null($message)) {
            return \responseJson([
                'status' => false,
                'message' => 'Error code not found',
            ]);
        }

        return \responseJson([
            'status' => true,
            'code' => $code,
            'message' => $message,
        ]);
    }
}
