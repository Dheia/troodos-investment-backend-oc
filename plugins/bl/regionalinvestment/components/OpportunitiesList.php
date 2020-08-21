<?php

namespace BL\RegionalInvestment\Components;

use BL\RegionalInvestment\Models\InvestmentOpportunity;
use CMS\Classes\ComponentBase;

class OpportunitiesList extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'Opportunities List',
            'description' => 'List of investment opportunities as displayed in the platform'
        ];
    }

    public function onRun()
    {
    }

    public function init()
    {
    }

    public function onFilterItems()
    {
        $type = $this->param('type');
        $name = $this->param('name');

        $results = InvestmentOpportunity::getLocalizedByCommunitySlug($name);
        $items = $results["data"];
        return [
            '#pagination_content' => $this->renderPartial('components/pagination_content', [
                'items' => $items,
                'type' => 'opportunity',
            ]),
            '#pagination_nav' => $this->renderPartial('components/pagination_nav', [
                'results' => $results
            ])
        ];
    }
}
