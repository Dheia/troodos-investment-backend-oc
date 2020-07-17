<?php

namespace BL\Teams\FormWidgets;

use BackendAuth;
use Backend\Classes\FormWidgetBase;
use Config;

class UserProvider extends FormWidgetBase

{
  public function widgetDetails()
  {
    return [
      'name' => 'User provider',
      'description' => 'Associates model with specific user'
    ];
  }

  public function render()
  {
    $this->prepareVars();
    return $this->makePartial('widget');
  }

  public function prepareVars()
  {
    $this->vars['user_id'] = BackendAuth::getUser()->id;
  }

  public function getSaveValue($user_id)
  {
    //To force user id as the value of the field in case of manipulated user input
    return BackendAuth::getUser()->id;;
  }
}
