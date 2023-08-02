<?php

namespace App\Modules\Campaign;

use App\Modules\Basket;

class BuyMoreThan1000TL25PercentDiscountCampaign implements ICampaign
{
    public function isEligible(Basket &$basket): bool
    {
        // Eğer Sepet toplamı 1000 TL üzerindeyse

        $totalPrice = $basket->getTotalPrice();

        return $totalPrice >= 1000;
    }

    public function calculateDiscount(Basket &$basket): float
    {
        // Kampanyanın sağlayacağı indirimi hesapla

        $totalPrice = $basket->getTotalPrice();

        return round($totalPrice * 0.25, 2);
    }

    public function apply(Basket &$basket)
    {
        // Sepete kampanyayı uygula
        // (Sipariş toplamına %25 indirim uygula)

        $discount = $this->calculateDiscount($basket);

        $basket->setDiscount($discount);

        return $basket;
    }

    public function __toString()
    {
        return '1000 TL üzeri alışverişlerde %25 indirim';
    }
}
