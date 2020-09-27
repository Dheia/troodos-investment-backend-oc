<?php

namespace BL\RegionalInvestment\Controllers;

use RainLab\User\Facades\Auth;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\Input;
use BL\RegionalInvestment\Models\InvestmentOpportunity;
use BL\RegionalInvestment\Models\Favorite;

class FavoriteController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function apiGet()
    {
        if (Auth::check()) {
            return response()->json(['favorites' => Favorite::get()], 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['favorites' => []], 403, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function apiCheck()
    {
        if (Auth::check()) {
            $ioid = Input::get('ioid');
            $isFavorite = Favorite::check($ioid);
            return response()->json(['check' => $isFavorite], 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['check' => false], 403, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }

    public function apiToggle()
    {
        if (Auth::check()) {
            $ioid = Input::get('ioid');
            if (Favorite::check($ioid)) {
                $isFavorite = Favorite::remove($ioid) ? false : true;
            } else {
                $isFavorite = Favorite::add($ioid) ? true : false;
            }
            return response()->json(['isFavorite' => $isFavorite], 200, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
        }
        return response()->json(['isFavorite' => false], 403, ['Content-type' => 'application/json'], JSON_UNESCAPED_UNICODE);
    }
}
