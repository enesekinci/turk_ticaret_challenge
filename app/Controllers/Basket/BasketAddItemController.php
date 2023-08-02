<?php

namespace App\Controllers\Basket;

use App\Controllers\Controller;
use App\Modules\Basket;

class BasketAddItemController extends Controller
{
    public function add()
    {
        $productId = \request()->get('product_id');

        $isValid = validate(['product_id' => $productId], ['product_id' => 'required|integer']);

        if ($isValid !== true) {
            return responseJson([
                'success' => false,
                'errors' => $isValid,
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

        $totalQuantity = \basket()->getItemQuantity($product) + 1;

        if ($product->stock_quantity < $totalQuantity) {
            return responseJson([
                'success' => false,
                'errors' => [
                    'quantity' => 'Ürün stokta yeterli miktarda bulunmamaktadır.'
                ],
            ]);
        }

        \basket()->add($product, 1);

        return responseJson([
            'success' => true,
            'message' => 'Ürün sepete eklendi.',
        ]);
    }
}
