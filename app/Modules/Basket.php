<?php

namespace App\Modules;

use App\Models\Basket as BasketModel;
use App\Models\Product;
use App\Modules\Campaign\Campaign;

class Basket
{
    public static $instance;
    public $items = [];
    public $discount = 0;
    # kargo ücreti
    public float $shippingFee = 13.99;
    # bedava ürünler
    public array $freeItems = [];

    protected function __construct()
    {
        #TODO: user id oturum açan kullanıcıya göre alınacak.

        foreach (user()->getBasketItems() as $item) {
            $this->items[$item->product_id] = [
                'product' => Product::find($item->product_id),
                'quantity' => $item->quantity,
                'item_id' => $item->id,
            ];
        }
    }

    public static function getInstance(): Basket
    {
        if (self::$instance === null) {
            self::$instance = new Basket();
        }

        return self::$instance;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getItem(int|Product $productId): ?array
    {
        if ($productId instanceof Product) {
            $productId = $productId->id;
        }

        return $this->items[$productId] ?? null;
    }

    public function clear(): Basket
    {
        $this->items = [];

        # db'den de silinebilir.
        $basketItems = BasketModel::get('id', 'user_id = ' . user()->id);

        foreach ($basketItems as $item) {
            #TODO: bulk delete için where in kullanılabilir.
            BasketModel::delete($item->id);
        }
        return $this;
    }

    public function update(Product $product, int $quantity): Basket
    {
        $this->items[$product->id]['quantity'] = $quantity;

        $basketItem = BasketModel::find($this->items[$product->id]['item_id']);
        $basketItem->quantity = $quantity;
        $basketItem->save();

        return $this;
    }

    public function create(Product $product, int $quantity): Basket
    {
        BasketModel::create([
            'user_id' => user()->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
        ]);

        $this->items[$product->id]['product'] = $product;
        $this->items[$product->id]['quantity'] = $quantity;

        return $this;
    }

    public function remove(int $productId)
    {

        $basketItem = $this->getItem($productId);

        BasketModel::delete($basketItem['item_id']);

        unset($this->items[$productId]);

        return $this;
    }

    public function getItemQuantity(Product $product): int
    {
        return $this->items[$product->id]['quantity'];
    }

    public function add(Product $product, int $quantity = 1): Basket
    {
        if (isset($this->items[$product->id])) {
            $newQuantity = $this->items[$product->id]['quantity'] + $quantity;
            $this->update($product, $newQuantity);
            return $this;
        }

        $this->create($product, $quantity);

        return $this;
    }

    # Sepet toplam tutarı (kargo ücreti hariç ve indirimler hariç)
    public function getTotalPrice(): float
    {
        $totalPrice = 0;

        foreach ($this->items as $item) {
            $price = $item['product']->list_price;
            $quantity = $item['quantity'];

            $totalPrice += $price * $quantity;
        }

        $totalPrice = round($totalPrice, 2);

        return $totalPrice;
    }



    public function getTotalItemCounts(): int
    {
        $totalItemCounts = 0;

        foreach ($this->items as $item) {
            $totalItemCounts += $item['quantity'];
        }

        return $totalItemCounts;
    }

    public function setDiscount(float $discount): Basket
    {
        $this->discount = $discount;

        return $this;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    public function calculateShippingFee(): float
    {
        $totalPrice = $this->getTotalPrice();

        if ($totalPrice >= FREE_SHIPPING_LIMIT) {
            return 0.0;
        }

        return $this->shippingFee;
    }

    public function addFreeItem(Product $product): Basket
    {
        if (isset($this->freeItems[$product->id])) {
            $this->freeItems[$product->id]['quantity']++;
            return $this;
        }

        $this->freeItems[$product->id] = [
            'product' => $product,
            'quantity' => 1,
        ];

        return $this;
    }

    public function getFreeItems(): array
    {
        return $this->freeItems;
    }

    public function applyCampaigns()
    {
        Campaign::getInstance()->applyBestCampaign($this);
        return $this;
    }

    public function getFinalPrice(): float
    {
        $totalPrice = $this->getTotalPrice();

        $discount = $this->getDiscount();

        $shippingFee = $this->calculateShippingFee();

        return $totalPrice - $discount + $shippingFee;
    }
}
