<?php

namespace BL\Maps\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Bl\Maps\Widgets\MapMarkers;

class MapsController extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\ReorderController',
    ];

    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';

    public function __construct()
    {
        $this->setForm();
        parent::__construct();

        //bind Map widget
//        $map = new MapGps($this);
//        $map->alias = 'mapgps';
//        $map->bindToController();

        BackendMenu::setContext('BL.Maps', 'maps-main-menu-item', 'maps-side-menu-item');
    }

    /**
     *
     * Overriding create() method to add javascript for coordinates
     *
     * @param string $context
     */
    public function create($context = '') {
        $this->addMapAssets();
        return $this->asExtension('FormController')->create($context);
    }


    /**
     *
     * Overriding update() method to add javascript for coordinates
     * and javascript for checkbox searches
     *
     * @param $recordId
     * @param string $context
     * @return mixed
     */
    public function update($recordId, $context = '') {
        $this->addMapAssets();
        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function setForm()
    {
        if (get('type') == 'gps' || get('type') == 'img') {
            $this->formConfig = '/forms/config_form_' . get('type') . '.yaml';
        }
    }

    public function listExtendRecords($records)
    {
        foreach ($records as $record) {
            $record->id = $record->id . '?type=' . $record->type;
        }
    }

    /**
     * Adds scripts needed for map coords functionality
     */
    protected function addMapAssets() {
        $this->addCss('/plugins/bl/maps/assets/css/style.css');
        //add local map init script and google map script
        if (get('type') === 'gps') {
            $this->addJs('/plugins/bl/maps/assets/js/field_map.js');
            // UPD: not adding google scripts here because the page has now 'mapmarkers' widget that
            // also has google scripts. 'callback' parameter there also got 'markerCoordsMapInit' value.
            // See: plugins/bl/maps/formwidgets/MapMarkers.php

//            $key = (env('GOOGLE_MAPS_KEY')) ? 'key=' . env('GOOGLE_MAPS_KEY') . '&' : '';
//            $this->addJs(
//                'https://maps.googleapis.com/maps/api/js?' . $key . 'callback=markerCoordsMapInit',
//                [
//                    'defer',
//                ]
//            );

        } elseif (get('type') == 'img') {
            $this->addJs('/plugins/bl/maps/assets/js/field_image_preview.js');

            $this->addCss('/plugins/bl/maps/assets/leaflet/leaflet.css');
            $this->addJs('/plugins/bl/maps/assets/leaflet/leaflet.js');
            $this->addJs('/plugins/bl/maps/assets/leaflet/leaflet-color-markers/js/leaflet-color-markers.js');

        }
    }

}
