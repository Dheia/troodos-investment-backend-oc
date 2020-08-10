<?php

namespace BL\RegionalInvestment\Components;

use CMS\Classes\ComponentBase;

class InvestmentCard extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Investment Card',
      'description' => 'Card that shows details of an investment opportunity'
    ];
  }

  public function onRun()
  {
  }

  public function init()
  {
  }
}
