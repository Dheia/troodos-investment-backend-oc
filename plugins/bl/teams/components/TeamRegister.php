<?php

namespace BL\Teams\Components;

use CMS\Classes\ComponentBase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use BL\Teams\Models\Team;
use Backend\Facades\BackendAuth;

class TeamRegister extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Team Register',
      'description' => 'Form for users to register as new administrators!'
    ];
  }

  public function defineProperties()
  {
    return [
      'role' => [
        'title' => 'Default role id',
        'description' => 'Id of role assigned to newly registered user',
        'default' => 3,
        'validationPattern' => '^[0-9]+$',
        'validationMessage' => 'Only numbers allowed'
      ]
    ];
  }

  public function onRegister()
  {
    $validator = Validator::make(post(), [
      'first_name' => 'required|min:2',
      'last_name' => 'required|min:2',
      'name' => 'required|min:2',
      'email' => 'required|email|unique:backend_users',
      'password' => 'required|confirmed|min:6',
      'password_confirmation' => 'required|min:6'
    ]);

    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    } else {
      $user = BackendAuth::register([
        'first_name' => post('first_name'),
        'last_name' => post('last_name'),
        'login' => post('email'),
        'email' => post('email'),
        'password' => post('password'),
        'password_confirmation' => post('password'),
        'is_activated' => 1
      ]);
      $team = new Team;
      $team->name = post('name');
      $team->email = post('email');
      $team->user_id = $user->id;
      $team->save();
      $user->team_id = $team->id;
      $user->role_id = $this->property('role');
      $user->save();
      BackendAuth::login($user);
      return Redirect::to('backend');
    }
  }
}
