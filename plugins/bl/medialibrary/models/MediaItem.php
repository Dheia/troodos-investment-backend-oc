<?php

namespace BL\MediaLibrary\Models;

use System\Models\File;
use BL\Teams\Scopes\TeamScope;
use Model;

/**
 * Model
 */
class MediaItem extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Validation;

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'system_files';

    /**
     * @var array Validation rules
     */
    public $rules = [];

    /**
     * Softly implement the TranslatableModel behavior.
     */
    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel', '@BL.CategoriesTags.Behaviors.HasTagsAndCategoriesModel', '@BL.Teams.Behaviors.TeamOwnedModel'];

    public $translatable = ['title', 'description'];

    public function scopePhoto($query)
    {
        return $query->where('field', 'photos');
    }

    public function scopeAudio($query)
    {
        return $query->where('field', 'audios');
    }

    public function scopeVideo($query)
    {
        return $query->where('field', 'videos');
    }

    public function scopeDocument($query)
    {
        return $query->where('field', 'documents');
    }

    public $belongsTo = [
        'media_library' => [
            'BL\MediaLibrary\Models\MediaLibrary',
            'key' => 'attachment_id'
        ]
    ];

    public function beforeDelete()
    {
        $file = File::where('id', $this->id)->first();
        $file->delete();
    }
}
