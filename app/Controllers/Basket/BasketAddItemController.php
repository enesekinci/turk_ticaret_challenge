<?php

namespace App\Controllers\Basket;

use App\Controllers\Controller;
use App\Modules\Basket;

class BasketAddItemController extends Controller
{
    public function add()
    {
        $productId = \request()->get('product_id');
        $quantity = \request()->get('quantity');

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
            return responseJson([
                'success' => false,
                'errors' => [
                    'quantity' => 'Ürün adedi 0\'dan büyük olmalıdır.'
                ],
            ]);
        }

        $product = \App\Models\Product::find($productId);

        if (!$product) {
            return responseJson([
                'success' => false,
                'errors' => [
                    'product_id' => 'Ürün bulunamadı.'
                ],
            ]);
        }

        #TODO: oluşabilecek hatalar için try catch kullanılabilir.

        $totalQuantity = \basket()->getItemQuantity($product) + $quantity;

        if ($product->stock_quantity < $totalQuantity) {
            return responseJson([
                'success' => false,
                'errors' => [
                    'quantity' => 'Ürün stokta yeterli miktarda bulunmamaktadır.'
                ],
            ]);
        }

        \basket()->add($product, $quantity);

        return responseJson([
            'success' => true,
            'message' => 'Ürün sepete eklendi.',
        ]);
    }
}
