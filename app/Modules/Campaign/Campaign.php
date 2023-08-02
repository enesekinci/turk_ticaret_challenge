<?php

namespace App\Modules\Campaign;

use App\Models\Traits\Jsonable;
use App\Modules\Basket;

class Campaign
{
    use Jsonable;

    protected static $instance;
    protected static $campaigns = [];

    protected function __construct()
    {
        self::$campaigns = [
            new BuyMoreThan1000TL25PercentDiscountCampaign(),
            new SabahattinAliCampaign(),
        ];
    }

    public static function getInstance(): Campaign
    {
        if (self::$instance === null) {
            self::$instance = new Campaign();
        }

        return self::$instance;
    }

    public function list()
    {
        return self::$campaigns;
    }

    public function add(ICampaign $campaign)
    {
        self::$campaigns[] = $campaign;
    }

    public function applyBestCampaign(Basket &$basket)
    {
        $eligibleCampaigns = [];
        $maxDiscount = 0;

        foreach (self::$campaigns as $campaign) {

            if ($campaign->isEligible($basket)) {

                $discount = $campaign->calculateDiscount($basket);

                if ($discount > $maxDiscount) {
                    $maxDiscount = $discount;
                    $eligibleCampaigns = [$campaign];
                } elseif ($discount === $maxDiscount) {
                    $eligibleCampaigns[] = $campaign;
                }
            }
        }

        if (count($eligibleCampaigns) > 0) {
            // En uygun kampanyayı uygula
            $chosenCampaign = $eligibleCampaigns[0];
            $chosenCampaign->apply($basket);
        }

        return self::getInstance();
    }
}
