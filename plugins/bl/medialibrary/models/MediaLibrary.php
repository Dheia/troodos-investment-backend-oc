<?php

namespace BL\MediaLibrary\Models;

use Backend\Facades\BackendAuth;
use BL\CategoriesTags\Models\Category;
use Illuminate\Support\Facades\Cache;
use BL\Teams\Scopes\TeamScope;
use Model;

/**
 * Model
 */
class MediaLibrary extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Purgeable;
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_medialibrary_media_library';

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel', '@BL.Teams.Behaviors.TeamOwnedModel'];

    protected $purgeable = ['category', 'tags'];

    public $translatable = ['title', 'description'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
        'title' => 'max:128',
        'description' => 'max:512',
    ];

    public function beforeSave()
    {
        return $this;
    }

    public function afterSave()
    {
        $key = BackendAuth::getUser() . '.file.batch';
        $value = [
            'id' => $this->id,
            'category' => $this->getOriginalPurgeValue('category'),
            'tags' => $this->getOriginalPurgeValue('tags')
        ];
        Cache::put($key, $value, 25);
        return $this;
    }

    public $hasMany = [
        'media_items' => [
            'BL\MediaLibrary\Models',
            'key' => 'attachment_id'
        ]
    ];

    public $attachMany = [
        'photos' => 'System\Models\File',
        'videos' => 'System\Models\File',
        'audios' => 'System\Models\File',
        'documents' => 'System\Models\File'
    ];

    public function getCategoryOptions($value, $formData)
    {
        $category_options = ['undefined' => ''];
        $db_category_options = Category::get();
        foreach ($db_category_options as $db_category_option) {
            $category_options[$db_category_option->id] = $db_category_option->name;
        }
        return $category_options;
    }
}
