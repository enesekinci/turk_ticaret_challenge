<?php

namespace App\Modules\Campaign;

use App\Modules\Basket;

interface ICampaign
{
    public function isEligible(Basket &$basket): bool;
    public function calculateDiscount(Basket &$basket): float;
    public function apply(Basket &$basket);
}
