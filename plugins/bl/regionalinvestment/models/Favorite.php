<?php

namespace BL\RegionalInvestment\Models;

use RainLab\User\Facades\Auth;
use BL\RegionalInvestment\Models\InvestmentOpportunity;
use Illuminate\Support\Facades\Input;
use Model;

/**
 * Model
 */
class Favorite extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_favorites';
    public $implement = [];
    public $timestamps = false;

    public $belongsToMany = [
        'members' => [
            'Backend\Models\User',
            'table' => 'bl_teams_team_user'
        ],
        'investment_opportunities' => [
            'BL\RegionalInvestment\Models\InvestmentOpportunity',
            'table'    => 'bl_regionalinvestment_investment_opportunities'
        ]
    ];

    public static function get(){
        $locale = Input::get('locale');
        $favorite_ids = Favorite::where('user_id', Auth::getUser()->id)->get()->pluck('i_o_id');
        $favorites = InvestmentOpportunity::with('business_types')->where('published', 1)->whereIn('id', $favorite_ids)->get();
        foreach ($favorites as $favorite) {
            $favorite->translateContext($locale);
        }
        return $favorites;
    }

    public static function check($ioid)
    {
        $favorite = self::where(['i_o_id' => $ioid, 'user_id' => Auth::getUser()->id])->first();
        if ($favorite) {
            return true;
        }
        return false;
    }

    public static function remove($ioid)
    {
        return self::where(['i_o_id' => $ioid, 'user_id' => Auth::getUser()->id])->delete();
    }

    public static function add($ioid)
    {
        $favorite = new self;
        $favorite->i_o_id = $ioid;
        $favorite->user_id = Auth::getUser()->id;
        return $favorite->save();
    }
}
