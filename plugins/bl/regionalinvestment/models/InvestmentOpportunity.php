<?php

namespace BL\RegionalInvestment\Models;

use Illuminate\Support\Facades\App;
use Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Model
 */
class InvestmentOpportunity extends Model
{
    use \October\Rain\Database\Traits\Validation;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_investment_opportunities';
    public $implement = [
        'RainLab.Translate.Behaviors.TranslatableModel',
        '@BL.CategoriesTags.Behaviors.HasTagsAndCategoriesModel',
        'Bl.Maps.Behaviors.PositionableOnMapModel'
    ];

    public $belongsToMany = [
        'communities' => [
            'BL\RegionalInvestment\Models\Community',
            'table'    => 'bl_regionalinvestment_community_i_o',
            'key'      => 'i_o_id',
            'otherKey' => 'c_id'
        ],
        'regions' => [
            'BL\RegionalInvestment\Models\Region',
            'table'    => 'bl_regionalinvestment_i_o_region',
            'key'      => 'i_o_id',
            'otherKey' => 'r_id'
        ],
        'business_types' => [
            'BL\RegionalInvestment\Models\BusinessType',
            'table'    => 'bl_regionalinvestment_business_type_i_o',
            'key'      => 'i_o_id',
            'otherKey' => 'b_t_id'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_investment_opportunities,slug',
        'short_description' => 'required|max:512',
        'investment_target' => 'required',
        'description' => 'required',
        'email' => 'email',
        'website' => 'url'
    ];

    public $translatable = ['name', 'short_description', 'description', 'sections', 'contact_details'];
    protected $jsonable = ['sections'];

    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    public static function getLocalized()
    {
        $opportunities = Cache::rememberForever("all.Opportunities" . App::getLocale(), function () {
            return self::with('business_types')->where('published', 1)->get()->toArray();
        });
        return collect($opportunities);
    }

    public static function getLocalizedByCommunity($community_id)
    {
        $opportunities = self::getLocalized();
        $ids = DB::table('bl_regionalinvestment_community_i_o')
            ->where('c_id', $community_id)
            ->pluck('i_o_id');
        return $opportunities->whereIn('id', $ids);
    }

    public static function getLocalizedByRegion($region_id)
    {
        $opportunities = self::getLocalized();
        $ids = DB::table('bl_regionalinvestment_i_o_region')
            ->where('r_id', $region_id)
            ->pluck('i_o_id');
        return $opportunities->whereIn('id', $ids);
    }

    public function afterSave()
    {
        Cache::forget("all.Opportunitiesen");
        Cache::forget("all.Opportunitiesel");
    }
}
