<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class FeaturedOpportunity extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Featured opportunity element',
      'description' => 'Detailed view of an investment opportunity from the database'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
