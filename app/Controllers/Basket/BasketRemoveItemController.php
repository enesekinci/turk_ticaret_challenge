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

        $isValid = validate(['product_id' => $productId], ['product_id' => 'required|integer']);

        if ($isValid !== true) {
            return responseJson([
                'success' => false,
                'errors' => $isValid,
            ]);
        }

        $basketItem = \basket()->getItem($productId);

        if ($basketItem === null) {
            return \responseJson([
                'status' => false,
                'errors' => [
                    'product_id' => 'Ürün sepetinizde bulunmamaktadır.'
                ],
            ]);
        }

        $product = $basketItem['product'];

        if ($quantity === 0) {

            \basket()->remove($productId);

            return \responseJson([
                'status' => true,
                'message' => 'Ürün sepetten kaldırıldı.',
            ]);
        }

        $totalQuantity = \basket()->getItemQuantity($product) - 1;

        #TODO: burada yeni miktarı stok durumuna göre uygun hale getirebilir.(Eğer sepetteki kadar ürün kalmadıysa komple kaldırılabilir.)

        if ($product->stock_quantity < $totalQuantity) {

            basket()->remove($product);

            return \responseJson([
                'status' => false,
                'errors' => [
                    'quantity' => 'Ürün stokta yeterli miktarda bulunmamaktadır.'
                ],
            ]);
        }


        \basket()->update($product, $totalQuantity);

        return \responseJson([
            'status' => true,
            'message' => 'Ürün sepetten kaldırıldı.',
        ]);
    }
}
