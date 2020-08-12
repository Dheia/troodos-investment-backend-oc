<?php

namespace BL\RegionalInvestment;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'BL\RegionalInvestment\Components\CommunitiesList' => 'communitiesList',
            'BL\RegionalInvestment\Components\Featured' => 'featured',
            'BL\RegionalInvestment\Components\InvestmentSearch' => 'investmentSearch',
            'BL\RegionalInvestment\Components\OpportunitiesList' => 'opportunitiesList',
            'BL\RegionalInvestment\Components\PointsOfInterestList' => 'pointsOfInterestList',
            'BL\RegionalInvestment\Components\RegionsList' => 'regionsList',
            'BL\RegionalInvestment\Components\SuccessStoriesList' => 'successStoriesList',
        ];
    }

    public function registerSettings()
    {
    }
}
