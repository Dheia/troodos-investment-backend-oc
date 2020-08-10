<?php

namespace BL\CategoriesTags\Models;

use Backend\Facades\BackendAuth;
use Model;

/**
 * Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;
    use \October\Rain\Database\Traits\Sortable;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $translatable = ['name'];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_categoriestags_categories';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $hasOne = [
        'item' => 'BL\CHDB\Models\Item',
        'file' => 'System\Models\File',
    ];

    public function beforeSave()
    {
        $this->code = str_slug($this->name, '-');
    }
}
