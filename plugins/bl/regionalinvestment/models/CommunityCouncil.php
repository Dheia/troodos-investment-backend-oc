<?php

namespace BL\RegionalInvestment\Models;

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

    public $belongsTo = [
        'community' => 'BL\RegionalInvestment\Models\Community'
    ];

    public $translatable = ['name', 'description', 'sections', 'slug', 'contact_details'];

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
}
