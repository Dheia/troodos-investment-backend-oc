<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class PointsOfInterestList extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Points Of Interest List',
      'description' => 'List of added value items as displayed in the platform'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
