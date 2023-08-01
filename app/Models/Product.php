<?php

namespace App\Models;

class Product extends BaseModel
{
    protected $table = 'products';

    protected $columns = [
        'id',
        'title',
        'category_id',
        'author',
        'list_price',
        'stock_quantity',
    ];

    protected $fillable = [
        'title',
        'category_id',
        'author',
        'list_price',
        'stock_quantity',
    ];

    protected $jsonable = [
        'id',
        'title',
        'category_id',
        'author',
        'list_price',
        'stock_quantity',
    ];
}
