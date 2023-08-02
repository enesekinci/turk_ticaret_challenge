<?php

use App\Modules\Campaign\BuyMoreThan1000TL25PercentDiscountCampaign;
use App\Modules\Campaign\Campaign;
use App\Modules\Campaign\SabahattinAliCampaign;

if (!function_exists('loadCampaigns')) {
    function loadCampaigns()
    {
        // $campaigns = [
        //     new BuyMoreThan1000TL25PercentDiscountCampaign(),
        //     new SabahattinAliCampaign(),
        // ];

        // foreach ($campaigns as $campaign) {
        //     Campaign::getInstance()->add($campaign);
        // }
        Campaign::getInstance()->add(new BuyMoreThan1000TL25PercentDiscountCampaign());
        Campaign::getInstance()->add(new SabahattinAliCampaign());
    }
}
