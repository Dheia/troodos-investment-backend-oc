<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class InvestmentSearch extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Investment search bar',
      'description' => 'Search bar for finding investment opportunities'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
