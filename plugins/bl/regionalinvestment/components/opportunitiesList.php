<?php

namespace BL\RegionalInvestment\Components;

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
}
