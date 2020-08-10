<?php

namespace BL\CategoriesTags;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use BL\CHDB\Models\Item;
use Illuminate\Support\Facades\Lang;
use BL\MediaLibrary\Models\MediaItem;
use System\Classes\PluginBase;

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
        // Extend all backend form usage
        Event::listen('backend.form.extendFields', function ($widget) {

            // Only for the User controller
            if (!$widget->getController() instanceof BL\Teams\Controllers\TeamsController) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof BL\Teams\Models\Team) {
                return;
            }

            // Add an extra birthday field
            $widget->addFields([
                'portal' => [
                    'label'   => Lang::get(),
                    'comment' => 'Select the users birthday',
                    'type'    => 'datepicker'
                ]
            ]);
        });
    }
}
