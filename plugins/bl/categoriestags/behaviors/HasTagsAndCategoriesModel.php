<?php

namespace BL\CategoriesTags\Behaviors;

use Illuminate\Support\Facades\DB;
use October\Rain\Extension\ExtensionBase;

/**
 * Base class for model behaviors.
 *
 * @package bl\guides
 * @author Christos Symeou
 */
class HasTagsAndCategoriesModel extends ExtensionBase
{

    protected $parent;

    public function __construct($parent)
    {
        $this->parent = $parent;
        $this->parent->morphToMany['tags'] = [
            'BL\CategoriesTags\Models\Tag',
            'name' => 'tagable',
            'table' => 'bl_categoriestags_tagables',
            'key' => 'item_id',
            'otherKey' => 'tag_id'
        ];
        $model = $this->parent;
        $this->parent->belongsTo['category'] = ['BL\CategoriesTags\Models\Category'];
        $this->parent->bindEvent('model.beforeDelete', function () use ($model) {
            DB::table('infopoint.bl_categoriestags_tagables')->where(['item_id' => $model->id, 'tagable_type' => get_class($model)])->delete();
        });
    }

    public function beforeDelete()
    {
    }
}
