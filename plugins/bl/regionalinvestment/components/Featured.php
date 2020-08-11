<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class Featured extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Featured element',
      'description' => 'Detailed view of an item from the database'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
