<?php

namespace App\Models;

class Category extends BaseModel
{
    protected $table = 'categories';
    protected $columns = [
        'id',
        'title',
    ];

    protected $fillable = [
        'title'
    ];

    protected $jsonable = [
        'id',
        'title',
    ];
}
