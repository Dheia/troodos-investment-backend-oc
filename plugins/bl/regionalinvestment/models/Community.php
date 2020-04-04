<?php

namespace BL\RegionalInvestment\Models;

use Model;
use October\Rain\Database\Traits\Sortable;

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
    protected $jsonable = ['sections'];
    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_regionalinvestment_communities';

    public $belongsTo = [
        'region' => 'BL\RegionalInvestment\Models\Region'
    ];

    protected $fillable = ['fdsa'];

    public $hasOne = [
        'community_council' => 'BL\RegionalInvestment\Models\CommunityCouncil'
    ];

    public $belongsToMany = [
        'investment_oppotunities' => [
            'BL\RegionalInvestment\Models\InvestmentOpportunity',
            'table'    => 'bl_regionalinvestment_community_investment_opportunity',
            'key'      => 'community_id',
            'otherKey' => 'investment_opportunity_id'
        ],
        'points_of_interest' => [
            'BL\RegionalInvestment\Models\PointOfInterest',
            'table'    => 'bl_regionalinvestment_community_point_of_interest',
            'key'      => 'community_id',
            'otherKey' => 'point_of_interest_id'
        ]
    ];

    public $translatable = ['name', 'description', 'sections', 'slug'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bl_regionalinvestment_communities,slug',
        'website' => 'url'
    ];
}
