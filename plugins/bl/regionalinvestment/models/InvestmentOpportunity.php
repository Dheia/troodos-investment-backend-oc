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

    public static $perPageDefault = 10;

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

    public static function getInput()
    {
        $input_all = Input::all();
        if (array_key_exists('_token', $input_all)) unset($input_all['_token']);
        if (array_key_exists('_session_key', $input_all)) unset($input_all['_session_key']);
        if (!array_key_exists('page', $input_all)) $input_all['page'] = 1;
        // See also perPageDefault in pagination_nav.htm
        if (!array_key_exists('per_page', $input_all)) $input_all['per_page'] = self::$perPageDefault;
        $business_types = [];
        foreach ($input_all as $key => $value) {
            if (preg_match('#^business_type#', $key) === 1) {
                $business_types[] = $value;
            }
        }
        return [
            'input_all' => $input_all,
            'page' => Input::get('page', 1),
            'per_page' => Input::get('per_page', self::$perPageDefault),
            'cache_key' => md5(serialize($input_all)),
            'business_types' => $business_types
        ];
    }

    public static function getLocalized()
    {
        $input = self::getInput();
        $opportunities = Cache::tags(['opportunities'])->rememberForever("all.Opportunities." . $input['cache_key'] . "." . App::getLocale(), function () use ($input) {
            $q = self::getFilterQuery($input);

            return $q->paginate($input['per_page'], $input['page'])->toArray();
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

    public static function getFilterQuery($input)
    {
        $q =  self::with('business_types');
        if (count($input['business_types']) > 0) {
            $q->whereHas('business_types', function ($q) use ($input) {
                $q->whereIn('b_t_id', $input['business_types']);
            });
        }
        if (!empty($input['input_all']['opportunity_name'])) {
            $q->transWhere('name', '%' . $input['input_all']['opportunity_name'] . '%', null, 'like');
        }
        if (!empty($input['input_all']['investment_target'])) {
            $q->where('investment_target', '>=', $input['input_all']['investment_target']);
        }
        if (!empty($input['input_all']['available_since'])) {
            $q->whereDate('date_available', '>=', date('Y-m-d', strtotime($input['input_all']['available_since'])));
        }
        if (!empty($input['input_all']['community'])) {
            $ids = DB::table('bl_regionalinvestment_community_i_o')
                ->where('c_id', $input['input_all']['community'])
                ->pluck('i_o_id');
            $q = $q->whereIn('bl_regionalinvestment_investment_opportunities.id', $ids);
        }
        $q->where('published', 1);
        return $q;
    }

    public static function getLocalizedByCommunity($community_id)
    {
        $input = self::getInput();
        $input['input_all']['community'] = $community_id;
        $opportunities = Cache::tags(['opportunities'])->rememberForever("community." . $community_id . ".Opportunities." . $input['cache_key'] . "." . App::getLocale(), function () use ($input, $community_id) {
            $q = self::getFilterQuery($input);
            return $q->paginate($input['per_page'], $input['page'])->toArray();
        });
        return $opportunities;
    }

    public static function getLocalizedByRegion($region_id)
    {
        $input = self::getInput();
        $opportunities = Cache::tags(['opportunities'])->rememberForever("region." . $region_id . ".Opportunities." . $input['cache_key'] . "." . App::getLocale(), function () use ($input, $region_id) {
            $q = self::getFilterQuery($input);
            $ids = DB::table('bl_regionalinvestment_i_o_region')
                ->where('r_id', $region_id)
                ->pluck('i_o_id');
            return $q->whereIn('id', $ids)->paginate($input['per_page'], $input['page'])->toArray();
        });
        return $opportunities;
    }

    public function afterSave()
    {
        Cache::tags(['opportunities'])->flush();
    }
}
