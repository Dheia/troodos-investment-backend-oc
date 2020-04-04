<?php

namespace BL\RegionalInvestment\Models;

use Model;
use October\Rain\Database\Traits\Sortable;

/**
 * Model
 */
class Region extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;
    protected $jsonable = ['sections'];
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_regions';

    public $belongsToMany = [
        'investment_oppotunities' => [
            'BL\RegionalInvestment\Models\InvestmentOpportunity',
            'table'    => 'bl_regionalinvestment_investment_opportunity_region',
            'key'      => 'region_id',
            'otherKey' => 'investment_opportunity_id'
        ],
        'organizations' => [
            'BL\RegionalInvestment\Models\Organization',
            'table'    => 'bl_regionalinvestment_organization_region',
            'key'      => 'region_id',
            'otherKey' => 'organization_id'
        ]
    ];

    public $hasMany = [
        'communities' => 'BL\RegionalInvestment\Models\Community',
        'points_of_interest' =>  'BL\RegionalInvestment\Models\PointOfInterest',
    ];

    public $translatable = ['name', 'description', 'sections', 'slug'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_regions,slug',
        'description' => 'required',
        'website' => 'url'
    ];
}
