<?php

namespace BL\RegionalInvestment\Models;

use Model;
use October\Rain\Database\Traits\Sortable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

/**
 * Model
 */
class Organization extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_organizations';

    public $belongsToMany = [
        'regions' => [
            'BL\RegionalInvestment\Models\Region',
            'table'    => 'bl_regionalinvestment_organization_region',
            'key'      => 'organization_id',
            'otherKey' => 'region_id'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_organizations,slug',
        'description' => 'required',
        'email' => 'email',
        'website' => 'url'
    ];

    public $translatable = ['name', 'description', 'sections', 'contact_details'];

    public static function getLocalized()
    {
        $organizations = Cache::rememberForever("all.Organizations" . App::getLocale(), function () {
            return self::where('published', 1)->get()->toArray();
        });
        return collect($organizations);
    }

    public static function getLocalizedByRegion($region_id)
    {
        $organizations = self::getLocalized();
        $ids = DB::table('bl_regionalinvestment_organization_region')
            ->where('region_id', $region_id)
            ->pluck('organization_id');
        return $organizations->whereIn('id', $ids);
    }

    public function afterSave()
    {
        Cache::forget("all.Organizationsen");
        Cache::forget("all.Organizationsel");
    }
}
