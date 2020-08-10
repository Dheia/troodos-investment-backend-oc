<?php

namespace BL\CategoriesTags\Models;

use Model;

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

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_categoriestags_tags';
    protected $fillable = ['name', 'slug'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $morphedByMany = [];

    public function beforeSave()
    {
        $this->slug = str_slug($this->name, '-');
    }
}
