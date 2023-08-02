<?php

namespace App\Controllers\Basket;

use App\Controllers\Controller;
use App\Models\Product;

class BasketRemoveItemController extends Controller
{
    public function remove()
    {
        $productId = request()->get('product_id');
        $quantity = request()->get('quantity');

        $isValid = validate([
            'product_id' => $productId,
            'quantity' => $quantity,
        ], [
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
        ]);

        if ($isValid !== true) {
            return responseJson([
                'success' => false,
                'errors' => $isValid,
            ]);
        }

        if ($quantity <= 0) {
            \basket()->remove($productId);
            return \responseJson([
                'status' => true,
                'message' => 'Ürün sepetten kaldırıldı.',
            ]);
        }

        $product = \basket()->getItem($productId)['product'];

        $totalQuantity = \basket()->getItemQuantity($product) + $quantity;

        #TODO: burada yeni miktarı stok durumuna göre uygun hale getirebilir.
        if ($product->stock_quantity < $totalQuantity) {
            return responseJson([
                'success' => false,
                'errors' => [
                    'quantity' => 'Ürün stokta yeterli miktarda bulunmamaktadır.'
                ],
            ]);
        }

        \basket()->update($product, $quantity);

        return \responseJson([
            'status' => true,
            'message' => 'Ürün sepetten kaldırıldı.',
        ]);
    }
}
