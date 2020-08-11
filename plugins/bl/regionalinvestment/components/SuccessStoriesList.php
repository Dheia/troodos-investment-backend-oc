<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class SuccessStoriesList extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Success Stories List',
      'description' => 'List of success stories as displayed in the platform'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
