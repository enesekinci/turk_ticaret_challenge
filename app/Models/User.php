<?php

namespace App\Models;

class User extends BaseModel
{
    protected $table = 'users';
    protected $columns = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $jsonable = [
        'name',
        'email',
    ];
}
