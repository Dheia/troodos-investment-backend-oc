<?php

namespace BL\RegionalInvestment\Models;

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
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

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
            'table'    => 'bl_regionalinvestment_community_point_of_interest',
            'key'      => 'point_of_interest_id',
            'otherKey' => 'community_id'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_business_entities,slug',
        'description' => 'required',
        'website' => 'url'
    ];

    public $translatable = ['name', 'description', 'slug'];
}
