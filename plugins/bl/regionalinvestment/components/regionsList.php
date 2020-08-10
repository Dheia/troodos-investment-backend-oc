<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class RegionsList extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Regions List',
      'description' => 'List of regions as displayed in the platform'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
