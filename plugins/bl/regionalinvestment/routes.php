<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

App::before(function ($request) {

  if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == "https") {
    URL::forceScheme("https");
  }

  Route::post('api/favorites/check', 'Bl\RegionalInvestment\Controllers\FavoriteController@apiCheck')->middleware('web');
  Route::post('api/favorites/', 'Bl\RegionalInvestment\Controllers\FavoriteController@apiToggle')->middleware('web');
  Route::get('api/favorites/', 'Bl\RegionalInvestment\Controllers\FavoriteController@apiGet')->middleware('web');
});
