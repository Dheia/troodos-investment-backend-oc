<?php

namespace BL\RegionalInvestment\Models;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Model;
use October\Rain\Database\Traits\Sortable;

/**
 * Model
 */
class SuccessStory extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    public $implement = [
        'RainLab.Translate.Behaviors.TranslatableModel',
        'Bl.Maps.Behaviors.PositionableOnMapModel'
    ];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_success_stories';

    public $belongsTo = [
        'region' => 'BL\RegionalInvestment\Models\Region',
//        'map' => 'BL\Maps\Models\Map'
    ];

    public $belongsToMany = [
        'communities' => [
            'BL\RegionalInvestment\Models\Community',
            'table'    => 'bl_regionalinvestment_community_s_s',
            'key'      => 's_s_id',
            'otherKey' => 'c_id'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_success_stories,slug',
        'description' => 'required',
        'website' => 'url'
    ];

    public $translatable = ['name', 'description'];

    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    public static function getLocalized()
    {
        $pointsOfInterest = Cache::rememberForever("all.SuccessStories" . App::getLocale(), function () {
            return self::where('published', 1)->get()->toArray();
        });
        return collect($pointsOfInterest);
    }

    public static function getLocalizedByCommunity($community_id)
    {
        $pointsOfInterest = self::getLocalized();
        $ids = DB::table('bl_regionalinvestment_community_s_s')
            ->where('c_id', $community_id)
            ->pluck('s_s_id');
        return $pointsOfInterest->whereIn('id', $ids);
    }

    public function afterSave()
    {
        Cache::forget("all.SuccessStoriesen");
        Cache::forget("all.SuccessStoriesel");
    }

    public static function getMapSlug() {
        return "success_story_map";
    }

}
