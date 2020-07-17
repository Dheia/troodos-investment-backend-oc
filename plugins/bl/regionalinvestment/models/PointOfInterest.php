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
class PointOfInterest extends Model
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
    public $table = 'bl_regionalinvestment_points_of_interest';

    public $belongsTo = [
        'region' => 'BL\RegionalInvestment\Models\Region'
    ];

    public $belongsToMany = [
        'communities' => [
            'BL\RegionalInvestment\Models\Community',
            'table'    => 'bl_regionalinvestment_community_p_o_i',
            'key'      => 'p_o_i_id',
            'otherKey' => 'c_id'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_points_of_interest,slug',
        'description' => 'required',
        'website' => 'url'
    ];

    public $translatable = ['name', 'description'];

    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    public static function getLocalized()
    {
        $pointsOfInterest = Cache::rememberForever("all.PointsOfInterest" . App::getLocale(), function () {
            return self::where('published', 1)->get()->toArray();
        });
        return collect($pointsOfInterest);
    }

    public static function getLocalizedByCommunity($community_id)
    {
        $pointsOfInterest = self::getLocalized();
        $ids = DB::table('bl_regionalinvestment_community_p_o_i')
            ->where('c_id', $community_id)
            ->pluck('p_o_i_id');
        return $pointsOfInterest->whereIn('id', $ids);
    }

    public function afterSave()
    {
        Cache::forget("all.PointsOfInteresten");
        Cache::forget("all.PointsOfInterestel");
    }
}
