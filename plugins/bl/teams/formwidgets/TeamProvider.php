<?php

namespace BL\Teams\FormWidgets;

use BackendAuth;
use Backend\Classes\FormWidgetBase;
use Config;

class TeamProvider extends FormWidgetBase

{
  public function widgetDetails()
  {
    return [
      'name' => 'Team provider',
      'description' => 'Associates model with currently active team'
    ];
  }

  public function render()
  {
    $this->prepareVars();
    return $this->makePartial('widget');
  }

  public function prepareVars()
  {
    $this->vars['team_id'] = BackendAuth::getUser()->team_id;
  }

  public function getSaveValue($active_team)
  {
    //To force user id as the value of the field in case of manipulated user input
    return BackendAuth::getUser()->team_id;
  }
}
