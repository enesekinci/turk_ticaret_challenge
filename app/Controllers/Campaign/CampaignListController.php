<?php

namespace App\Controllers\Campaign;

use App\Modules\Campaign\Campaign;

class CampaignListController
{
    public function list()
    {
        return \responseJson([
            'status' => true,
            'campaigns' => Campaign::getInstance()->list(),
        ]);
    }
}
