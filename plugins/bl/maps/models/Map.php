<?php

namespace BL\Maps\Models;

use Illuminate\Support\Facades\Input;
use Model;

/**
 * Model
 */
class Map extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_maps_maps';

    public $timestamps = false;

    protected $fillable = ['name', 'type', 'coord_x', 'coord_y', 'zoom'];

    public $translatable = ['name'];

    public $implement = [
        'RainLab.Translate.Behaviors.TranslatableModel',
    ];

    public $belongsTo = [];

    public $belongsToMany = [];

    public $hasMany = [];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|max:64',
    ];

    public function beforeSave()
    {
        $this->type = Input::get('type');
    }

    public function getImagePathAttribute()
    {
        if ($this->image) {
            return $this->image->full_path;
        }
        return "";
    }
}
