<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class CommunitiesList extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Communities List',
      'description' => 'List of communities as displayed in the platform'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
