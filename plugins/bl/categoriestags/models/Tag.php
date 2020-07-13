<?php

namespace BL\CategoriesTags\Models;

use Backend\Facades\BackendAuth;
use Model;
use BL\Teams\Scopes\TeamScope;

/**
 * Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $implement = ['@BL.Teams.Behaviors.TeamOwnedModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_categoriestags_tags';
    protected $fillable = ['name', 'team_id', 'slug'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $morphedByMany = [
        'items'  => ['BL\CHDB\Models\Item', 'name' => 'tagable'],
        'mediaitems' => ['BL\MediaLibrary\Models\MediaItem', 'name' => 'tagable']
    ];

    public function scopeMediaTag($query)
    {
        return $query->has('mediaitems')->get();
    }

    public function beforeSave()
    {
        $this->slug = str_slug($this->name, '-');
    }
}
