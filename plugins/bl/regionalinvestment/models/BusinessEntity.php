<?php

namespace BL\RegionalInvestment\Models;

use Model;
use October\Rain\Database\Traits\Sortable;

/**
 * Model
 */
class BusinessEntity extends Model
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
    public $table = 'bl_regionalinvestment_business_entities';

    public $belongsTo = [
        'business_entity_status' => 'BL\RegionalInvestment\Models\BusinessEntityStatus',
        'business_type' => 'BL\RegionalInvestment\Models\BusinessType'
    ];

    public $hasMany = [
        'investment_opportunities' => 'BL\RegionalInvestment\Models\InvestmentOpportunity',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_business_entities,slug',
        'description' => 'required',
        'email' => 'email',
        'website' => 'url'
    ];

    public $translatable = ['name', 'description', 'sections', 'slug'];
}
