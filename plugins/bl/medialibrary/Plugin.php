<?php

namespace BL\MediaLibrary;

use Backend\Facades\BackendAuth;
use Illuminate\Support\Facades\Cache;
use BL\MediaLibrary\Models\MediaItem;
use BL\MediaLibrary\Models\MediaLibrary;
use System\Classes\PluginBase;
use BL\Teams\Models\Locale;
use BL\CHDB\Models\Tag;
use BL\Teams\Models\Team;
use System\Models\File;


class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Team::extend(function ($model) {
            $model->hasMany['media_libraries'] = ['bl\Medialibrary\Models\MediaLibrary'];
        });
        File::extend(function ($model) {
            $model->belongsTo['category'] = [
                'bl\MediaLibrary\Models\MediaCategory',
                'key' => 'category_id'
            ];
        });
        File::extend(function ($model) {
            $model->bindEvent('model.afterSave', function () use ($model) {
                if (BackendAuth::check()) {
                    $full_path = $model->getPath();
                    $mediaitem = MediaItem::where('id', $model->id)->first();
                    //Details to assign to file where cached when media library was updated
                    $details = Cache::get(BackendAuth::getUser() . '.file.batch');
                    //First set the category of the item
                    $mediaitem->category_id = is_array($details) && array_key_exists('category', $details) && $details['category'] != 'undefined' ? $details['category'] : null;
                    $mediaitem->full_path = $full_path;
                    $mediaitem->save();
                    //Then the rest of the items
                    $medialibrary = is_array($details) && array_key_exists('id', $details) ? MediaLibrary::where('id', $details['id'])->first() : false;
                    if ($medialibrary) {
                        $locales = Locale::get();
                        foreach ($locales as $locale) {
                            //Get initial values
                            $medialibrary->translateContext($locale->code);
                            $mediaitem->translateContext($locale->code);
                            $mediaitem->title = $medialibrary->title;
                            $mediaitem->description = $medialibrary->description;
                            $mediaitem->save();
                        }
                    }
                    //Deal with tags
                    $team_id = BackendAuth::getUser()->team_id;
                    $tags = is_array($details) && array_key_exists('tags', $details) ? explode(',', $details['tags']) : [];
                    foreach ($tags as $tag) {
                        if ($tag != '') {
                            $tag = Tag::firstOrCreate([
                                'name' => $tag,
                                'slug' => str_slug($tag, '-'),
                                'team_id' => $team_id
                            ]);
                            $mediaitem->tags()->attach($tag);
                        }
                    }
                }
            });
        });
    }
}
