<?php

namespace BL\RegionalInvestment\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
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

    public static $perPageDefault = 1;

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
    protected $jsonable = ['sections', 'photos'];

    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    public static function getInput(){
        $input_all = Input::all();
        if (array_key_exists('_token', $input_all)) unset($input_all['_token']);
        if (array_key_exists('_session_key', $input_all)) unset($input_all['_session_key']);
        if (!array_key_exists('page', $input_all)) $input_all['page'] = 1;
        // See also perPageDefault in pagination_nav.htm
        $perPageDefault = 1;
        if (!array_key_exists('per_page', $input_all)) $input_all['per_page'] = self::$perPageDefault;
        return [
            'input_all' => $input_all,
            'page' => Input::get('page', 1),
            'per_page' => Input::get('per_page', self::$perPageDefault),
            'cache_key' => md5(serialize($input_all))
        ];
    }

    public static function getLocalized()
    {
        $input = self::getInput();
        $opportunities = Cache::tags(['opportunities'])->rememberForever("all.Opportunities." . $input['cache_key'] . "." . App::getLocale(), function () use ($input) {
            return self::with('business_types')
                ->where('published', 1)->paginate($input['per_page'], $input['page'])->toArray();
        });
        return collect($opportunities);
    }

    public static function getLocalizedByCommunitySlug($slug)
    {
        $community = Community::where('slug', $slug)->first();
        if ($community)
            return self::getLocalizedByCommunity($community->id);
        return [];
    }

    public static function getLocalizedByCommunity($community_id)
    {
        $input = self::getInput();
        $opportunities = Cache::tags(['opportunities'])->rememberForever("community." . $community_id . ".Opportunities." . $input['cache_key'] . "." . App::getLocale(), function () use ($input, $community_id) {
            $q =  self::with('business_types')->where('published', 1);
            $ids = DB::table('bl_regionalinvestment_community_i_o')
                ->where('c_id', $community_id)
                ->pluck('i_o_id');
            return $q->whereIn('id', $ids)->paginate($input['per_page'], $input['page'])->toArray();
        });
        return $opportunities;
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
        Cache::tags(['opportunities'])->flush();
    }

}
