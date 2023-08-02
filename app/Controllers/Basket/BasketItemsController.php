<?php

namespace App\Controllers\Basket;

use App\Controllers\Controller;

class BasketItemsController extends Controller
{
    public function items()
    {
        $items = \basket()->getItems();

        \basket()->applyCampaigns();

        $discount = \basket()->getDiscount();

        $shippingFee = \basket()->calculateShippingFee();

        $totalPrice = \basket()->getTotalPrice();

        $freeItems = \basket()->getFreeItems();

        return \responseJson([
            'status' => true,
            'items' => $items,
            'discount' => $discount,
            'shippingFee' => $shippingFee,
            'totalPrice' => $totalPrice,
            'freeItems' => $freeItems,
        ]);
    }

    #TODO: bu işlem ayrı bir controller'a taşınabilir.
    public function clear()
    {
        \basket()->clear();

        return \responseJson([
            'status' => true,
            'message' => 'Sepet temizlendi.',
        ]);
    }
}
