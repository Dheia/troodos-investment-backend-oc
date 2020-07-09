<?php

namespace BL\RegionalInvestment\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\App;

/**
 * Model
 */
class Community extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    protected $jsonable = ['sections', 'photos'];
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_communities';

    public $belongsTo = [
        'region' => 'BL\RegionalInvestment\Models\Region'
    ];

    public $belongsToMany = [
        'investment_oppotunities' => [
            'BL\RegionalInvestment\Models\InvestmentOpportunity',
            'table'    => 'bl_regionalinvestment_community_i_o',
            'key'      => 'c_id',
            'otherKey' => 'i_o_id'
        ],
        'points_of_interest' => [
            'BL\RegionalInvestment\Models\PointOfInterest',
            'table'    => 'bl_regionalinvestment_community_p_o_i',
            'key'      => 'c_id',
            'otherKey' => 'p_o_i_id'
        ]
    ];

    public $translatable = ['name', 'description', 'sections'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_communities,slug',
        'website' => 'url'
    ];

    public $attachMany = [
        'gallery' => 'System\Models\File'
    ];

    public static function getLocalized()
    {
        $communities = Cache::rememberForever("all.Communities" . App::getLocale(), function () {
            return self::where('published', 1)->get()->toArray();
        });
        return collect($communities);
    }

    public function afterSave()
    {
        Cache::forget("all.Communitiesen");
        Cache::forget("all.Communitiesel");
    }
}
