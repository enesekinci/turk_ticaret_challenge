<?php

namespace App\Modules\Campaign;

use App\Modules\Basket;

class SabahattinAliCampaign implements ICampaign
{
    protected static $freeItemCount = 0;
    protected static $freeItems = [];

    public function isEligible(Basket &$basket): bool
    {
        // Eğer Sepette Sabahattin Ali kitabı varsa
        $items = $basket->getItems();
        $eligibleCount = 0;

        foreach ($items as $item) {
            if ($item['product']->author === 'Sabahattin Ali') {
                $eligibleCount++;
            }
        }

        return $eligibleCount >= 3;
    }

    public function calculateDiscount(Basket &$basket): float
    {
        // Kampanyanın sağlayacağı indirimi hesapla
        // Sabahattin Ali'nin Roman kitaplarında 3 üründen 1 tanesi ve 6 üründen 3 tanesi bedava.
        // (Bedava en fazla 4 kitap verilebilir. Örn: 10 adet alan 6 kitap parası öder.)

        $eligibleItems = [];
        $discount = 0;

        // Eligible items'ları belirle
        $items = $basket->getItems();
        foreach ($items as $item) {
            if ($item['product']->author === 'Sabahattin Ali') {
                $eligibleItems[] = $item['product'];
            }
        }

        // Kampanya için uygun ürün sayısını bul
        $eligibleItemCount = count($eligibleItems);

        // 6 üründen 3 tanesi bedava
        self::$freeItemCount = min(3, (int)($eligibleItemCount / 6)) * 3;

        // 3 üründen 1 tanesi bedava
        self::$freeItemCount += min(1, (int)(($eligibleItemCount - self::$freeItemCount) / 3));

        // Bedava ürünlerin toplam indirimini hesapla ve sepetteki ücretsiz ürünleri tut

        if (!self::$freeItemCount) {
            return 0;
        }

        $freeItemCount = self::$freeItemCount;

        foreach ($eligibleItems as $product) {
            if (!$freeItemCount > 0) {
                break;
            }

            $discount += $product->list_price;
            #TODO: burada seçilen ürünler en ucuz olanlar olmalı
            $freeItemCount--;
            //  ücretsiz ürünleri tut
            self::$freeItems[] = $product;
        }

        return $discount;
    }

    public function apply(Basket &$basket)
    {
        // Sepete kampanyayı uygula
        // (Sabahattin Ali kitaplarından 3 tanesi için 1 tanesini ve 6 tanesi için 3 tanesini bedava ekle)

        $discount = $this->calculateDiscount($basket);

        $basket->setDiscount($discount);

        foreach (self::$freeItems as $item) {
            $basket->addFreeItem($item);
        }

        return $basket;
    }

    public function __toString()
    {
        return 'Sabahattin Ali\'nin Roman kitaplarında 3 üründen 1 tanesi ve 6 üründen 3 tanesi bedava.';
    }
}
