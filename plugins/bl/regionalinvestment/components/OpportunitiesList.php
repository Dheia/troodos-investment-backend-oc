<?php

namespace BL\RegionalInvestment\Components;

use BL\RegionalInvestment\Models\InvestmentOpportunity;
use CMS\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;

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
        $this->page['per_page'] = Input::get('per_page');
    }

    public function init()
    {
    }

    public function onFilterItems()
    {
        $type = $this->param('type');
        $name = $this->param('name');

        $this->page['per_page'] = Input::get('per_page');

        $results = empty($name) ? InvestmentOpportunity::getLocalized() :  InvestmentOpportunity::getLocalizedByCommunitySlug($name);
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
