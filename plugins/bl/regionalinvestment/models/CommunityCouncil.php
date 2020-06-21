<?php

namespace BL\RegionalInvestment\Models;

use Illuminate\Support\Facades\Cache;
use Model;
use October\Rain\Database\Traits\Sortable;

/**
 * Model
 */
class CommunityCouncil extends Model
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
    public $table = 'bl_regionalinvestment_community_councils';

    public $hasOne = [
        'community' => 'BL\RegionalInvestment\Models\Community'
    ];

    public $translatable = ['name', 'description', 'sections', 'slug', 'contact_details'];

    protected $fillable = ['community_id'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_community_councils,slug',
        'description' => 'required',
        'email' => 'email',
        'website' => 'url'
    ];

    public function afterSave()
    {
        Cache::forget("all.Communitiesen");
        Cache::forget("all.Communitiesel");
    }
}
