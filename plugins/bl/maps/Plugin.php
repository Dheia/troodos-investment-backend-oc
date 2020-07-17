<?php

namespace BL\Maps;

use App;
use Config;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents() {
        return [
          'Bl\Maps\Components\MapMarkers' => 'mapMarkers',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerFormWidgets()
    {
        return [
            'BL\Maps\FormWidgets\MapMarkers' => [
                'label' => 'Map Markers',
                'code' => 'mapmarkers'
            ],

        ];
    }


    public function boot()
    {

    }


}
