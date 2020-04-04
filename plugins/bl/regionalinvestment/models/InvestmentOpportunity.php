<?php

namespace BL\RegionalInvestment\Models;

use Model;

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
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $belongsTo = [
        'business_entity' => 'BL\RegionalInvestment\Models\BusinessEntity'
    ];

    public $belongsToMany = [
        'communities' => [
            'BL\RegionalInvestment\Models\Community',
            'table'    => 'bl_regionalinvestment_community_investment_opportunity',
            'key'      => 'investment_opportunity_id',
            'otherKey' => 'community_id'
        ],
        'regions' => [
            'BL\RegionalInvestment\Models\Region',
            'table'    => 'bl_regionalinvestment_investment_opportunity_region',
            'key'      => 'investment_opportunity_id',
            'otherKey' => 'region_id'
        ],
        'business_types' => [
            'BL\RegionalInvestment\Models\BusinessType',
            'table'    => 'bl_regionalinvestment_business_type_investment_opportunity',
            'key'      => 'investment_opportunity_id',
            'otherKey' => 'business_type_id'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_investment_opportunities,slug',
        'short_description' => 'required|max:512',
        'investment_min' => 'required',
        'investment_max' => 'required',
        'description' => 'required',
        'email' => 'email',
        'website' => 'url'
    ];

    public $translatable = ['name', 'short_description', 'description', 'sections', 'slug', 'contact_details'];
    protected $jsonable = ['sections'];
}
