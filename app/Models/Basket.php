<?php

namespace App\Models;

class Basket extends BaseModel
{
    protected $table = 'baskets';

    protected $columns = [
        'id',
        'user_id',
        'product_id',
        'quantity',
    ];

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
    ];

    protected $jsonable = [
        'id',
        'user_id',
        'product_id',
        'quantity',
    ];

    public static function getItemsByUser(User $user)
    {
        return self::get('*', 'user_id = ' . $user->id . ' ', 'product_id ASC');
    }
}
