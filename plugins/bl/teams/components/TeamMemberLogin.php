<?php

namespace BL\Teams\Components;

use CMS\Classes\ComponentBase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Redirect;
use Backend\Models\User;
use Illuminate\Support\Facades\Lang;
use Backend\Facades\BackendAuth;

class TeamMemberLogin extends ComponentBase
{

  public function componentDetails()
  {
    return [
      'name' => 'Team members login',
      'description' => 'Form for team members to login to backend dashboard'
    ];
  }

  public function onLogin()
  {

    $validator = Validator::make(post(), [
      'email' => 'required|email',
      'password' => 'required'
    ]);


    if ($validator->fails()) {
      return Redirect::back()->withErrors($validator);
    } else {
      $user = User::where('email', post('email'))->first();
      if ($user) {
        if (Hash::check(post('password'), $user->password)) {
          BackendAuth::login($user);
          return Redirect::to('backend');
        } else {
          Flash::warning(Lang::get('bl.teams::lang.plugin.userNotFound'));
        }
      } else {
        Flash::warning(Lang::get('bl.teams::lang.plugin.userNotFound'));
      }
    }
  }
}
