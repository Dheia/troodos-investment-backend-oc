<?php

namespace BL\RegionalInvestment\Models;

use Model;
use October\Rain\Database\Traits\Sortable;

/**
 * Model
 */
class BusinessEntityStatus extends Model
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
    public $table = 'bl_regionalinvestment_business_entity_statuses';

    public $hasMany = [
        'business_entities' => 'BL\RegionalInvestment\Models\BusinessEntity'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_business_entity_statuses,slug',
    ];


    public $translatable = ['name', 'slug'];
}
