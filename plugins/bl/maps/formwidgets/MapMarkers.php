<?php

namespace Bl\Maps\FormWidgets;

use Backend\Classes\FormWidgetBase;
use BL\Maps\Models\Map;
use BL\Maps\Models\Position;
use Exception;
use Illuminate\Support\Facades\Lang;
use RainLab\Translate\Models\Locale;


class MapMarkers extends FormWidgetBase
{

    /**
     * @var string widget alias
     */
    protected $defaultAlias = 'mapmarkers';


    /**
     * @return array of widget info
     */
    public function widgetDetails()
    {
        return [
            'name' => 'bl.maps::lang.plugin.map_widget_name',
            'description' => 'bl.maps::lang.plugin.map_widget_description',
        ];
    }

    protected function prepareVars()
    {
        clearstatcache();

        $this->vars['model_id'] = $this->model->id;
        if ($this->model->isClassExtendedWith('BL.Teams.Behaviors.TeamOwnedModel'))
            $this->vars['maps'] = Map::where('team_id', $this->model->team_id)->get();
        else
            $this->vars['maps'] = Map::all();
        $this->vars['defaultLocale'] = Locale::getDefault()->code;
        $this->vars['listEnabledLocales'] = Locale::listEnabled();
    }


    /**
     *
     * Renders widget HTML
     *
     * @return mixed
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('body');
    }

    protected function loadAssets()
    {
        $this->addGmapJs();

        $this->addCss('/plugins/bl/maps/assets/css/style.css');
        $this->addCss('/plugins/bl/maps/formwidgets/mapmarkers/assets/css/mapmarkers.css');
        $this->addJs('/plugins/bl/maps/formwidgets/mapmarkers/assets/js/mapmarkers.js');

        $this->addCss('/plugins/bl/maps/assets/leaflet/leaflet.css');
        $this->addJs('/plugins/bl/maps/assets/leaflet/leaflet.js');
        $this->addJs('/plugins/bl/maps/assets/leaflet/leaflet-color-markers/js/leaflet-color-markers.js');
    }


    /**
     * Adds Google Map script and local asset script to page
     */
    protected function addGmapJs()
    {
        //Google Map
        $this->addJs('/plugins/bl/maps/formwidgets/mapmarkers/assets/js/gmaps.js');
        $key = (env('GOOGLE_MAPS_KEY')) ? 'key=' . env('GOOGLE_MAPS_KEY') . '&' : '';

        // UPD:
        // markerCoordsMapInit function is from 'plugins/bl/maps/assets/js/field_map.js' to init the map
        // on the first tab 'Map Details'
        $this->addJs(
            'https://maps.googleapis.com/maps/api/js?' . $key . 'callback=markerCoordsMapInit',
            [
                'async',
                'defer',
            ]
        );

        //Leaflet
        $this->addJs('/plugins/bl/maps/formwidgets/mapmarkers/assets/js/leafletmaps.js');
    }

    public function getMarkers() {
        $mapId = request('map_id');
        if ($mapId) {
            if (in_array('Bl.Maps.Behaviors.PositionableOnMapModel', $this->model->implement))
                $this->model->initPositionableContext($mapId);
            $markers = Position::where('map_id', $mapId)->with('translations')->get();
            foreach ($markers as $marker) {
                $marker->active = false;
                if ($marker->model_id == $this->model->id && $marker->model_type == $this->model->getMorphClass()) {
                    $marker->active = true;
                }
            }
            return $markers;
        }
        return [];
    }

    /**
     *
     * AJAX callback
     * Returns array of all markers in the system
     *
     * @return array
     */
    public function onMarkersLoad()
    {
        $mapId = request('map_id');
        if ($mapId) {
            $map = Map::where('id', $mapId)->with('image')->first();
            $markers = $this->getMarkers();
            return [
                'status' => 'success',
                'map' => $map,
                'markers' => $markers,
                'defaultLocale' => Locale::getDefault(),
                '#marker-list' => $this->makePartial('marker_list', [
                    'items' => $markers,
                ])
            ];
        }
        return ['status' => 'error'];
    }

    public function refreshMarkerList() {
        $markers = $this->getMarkers();
        return [
            '#marker-list' => $this->makePartial('marker_list', [
                'items' => $markers
            ])
        ];
    }

    public function onSaveMarker()
    {
        $mapId = request('map_id');
        if ($mapId) {

            $regex = '/^(https?:\/\/)?([\da-z\.\+-@=!:]+)\.([a-z\.]{2,6})([\/\w \.\+-@=!:]*)*\/?$/';
            try {
                request()->validate([
                    'external_url' => 'regex:' . $regex,
                ]);
            } catch (Exception $ex) {
                return [
                    'status' => 'error',
                    'message' => Lang::get('bl.maps::lang.maps.external_url_validation_failed')
                ];
            }

            // We can save not only current $this->model but also any other positionable model because
            // we can change active marker in the widget on the frontend. Therefore we have switch to the
            // correct model here and not using $this->model. $this->model is initial model for the
            // active marker on the load.
            if (is_numeric(request('marker_id'))) {
                $marker = Position::where('id', request('marker_id'))->first();
                $model = $marker->model_type::findOrFail($marker->model_id);
            } else {
                if (request('can_create') == '0' ) {
                    return ['status' => 'error', 'message' => Lang::get('bl.maps::lang.maps.cant_create')];
                }
                $model = $this->model;
            }
            $model->initPositionableContext($mapId);
            $model->setAttributePositionable('title', request('title'));
            $model->setAttributePositionable('description', request('description'));
            $model->setAttributePositionable('external_url', request('external_url'));
            $model->setAttributePositionable('coord_x', request('coord_x'));
            $model->setAttributePositionable('coord_y', request('coord_y'));
            $model->setAttributePositionable('translations', request('translations'));
            $markerId =  $model->storePositionableData();


            return [
                'status' => 'success',
                'marker_id' => $markerId,
                'message' => Lang::get('bl.maps::lang.maps.data_saved'),
                '#marker-list' => $this->makePartial('marker_list', [
                            'items' => $this->getMarkers()
                        ])
            ];
        }
        return ['status' => 'error'];
    }

    public function onDeleteMarker() {
        $mapId = request('map_id');
        if ($mapId) {

//            $markerId = request('marker_id');
//            try {
//                request()->validate([
//                    'marker_id' => 'required',
//                ]);
//            } catch (Exception $ex) {
//                return [
//                    'status' => 'error',
//                    'message' => Lang::get('bl.maps::lang.maps.marker_id_is_required')
//                ];
//            }

            // We can save not only current $this->model but also any other positionable model because
            // we can change active marker in the widget on the frontend. Therefore we have switch to the
            // correct model here and not using $this->model. $this->model is initial model for the
            // active marker on the load.
            if (is_numeric(request('marker_id'))) {
                $marker = Position::where('id', request('marker_id'))->first();
                $model = $marker->model_type::findOrFail($marker->model_id);
            } else {
                $model = $this->model;
            }
            $markerId = $model->deletePositionableData($mapId);


            return ['status' => 'success', 'marker_id' => $markerId, 'message' => Lang::get('bl.maps::lang.maps.data_deleted')];
        }
        return ['status' => 'error'];
    }

}
