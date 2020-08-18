<?php

namespace Bl\Maps\Components;

use BL\Guides\Models\Guide;
use BL\Maps\Models\Position;
use BL\Maps\Models\Map;
use BL\CHDB\Models\Item;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Illuminate\Database\Eloquent\Collection;
use Request;

class MapMarkers extends ComponentBase
{

    public function componentDetails()
    {
        return [
            'name' => 'bl.maps::lang.plugin.map_component_label',
            'description' => 'bl.maps::lang.plugin.map_component_description'
        ];
    }

    /**
     *
     * Define component properties
     *
     * @return array
     */
    public function defineProperties()
    {
        return [
            'mapId' => [
                'title'       => 'bl.maps::lang.maps.name',
                'description' => 'bl.maps::lang.maps.name',
                'type'        => 'string',
            ],
            'mapSlug' => [
                'title'       => 'bl.maps::lang.maps.name',
                'description' => 'bl.maps::lang.maps.name',
                'type'        => 'string',
            ],
            'itemIds' => [
                'title'       => 'bl.maps::lang.maps.item_ids',
                'description' => 'bl.maps::lang.maps.item_ids',
                'type'        => 'string',
            ],
        ];
    }

    public function determineMarkerUrl($marker)
    {
        $path =  '/investment-platform/';
        $model = $marker->model_type::findOrFail($marker->model_id);
        if ($marker->model_type == 'BL\RegionalInvestment\Models\Community') {
            $path =  $path . 'community/' . $model->slug;
        } else if ($marker->model_type == 'BL\RegionalInvestment\Models\Region') {
            $path =  $path . 'region/' . $model->slug;
        } else if ($marker->model_type == 'BL\RegionalInvestment\Models\InvestmentOpportunity') {
            $path =  $path . 'opportunity/' . $model->slug;
        } else if ($marker->model_type == 'BL\RegionalInvestment\Models\SuccessStory') {
            $path =  $path . 'successStory/' . $model->slug;
        } else if ($marker->model_type == 'BL\RegionalInvestment\Models\PointOfInterest') {
            $path =  $path . 'pointOfInterest/' . $model->slug;
        }
        return $path;
    }

    /**
     * Adds scripts needed for map functionality
     */
    protected function addMapAssets()
    {
        //add default styles for map
        $this->addCss('/plugins/bl/maps/assets/css/style.css');

        //add local map init script and google map script
        $this->addJs('/plugins/bl/maps/assets/js/component_map.js');

        //add google map js with or without api key
        $key = (env('GOOGLE_MAPS_KEY')) ? 'key=' . env('GOOGLE_MAPS_KEY') . '&' : '';
        $this->addJs(
            'https://maps.googleapis.com/maps/api/js?' . $key . 'callback=mapComponentInit',
            [
                'async',
                'defer',
            ]
        );

        $this->addCss('/plugins/bl/maps/assets/leaflet/leaflet.css');
        $this->addJs('/plugins/bl/maps/assets/leaflet/leaflet.js');
        $this->addJs('/plugins/bl/maps/assets/leaflet/leaflet-color-markers/js/leaflet-color-markers.js');
    }

    /**
     * onRun event
     */
    public function onRun()
    {
        $this->addMapAssets();
    }

    // Properties passed as {% component 'component_name' prop=val %} are available in this method not in 'onRun' because of the lifecycle of the page
    public function onRender()
    {
        $this->page->components['mapMarkers']->setProperty('mapId', $this->property('mapId'));
    }

    /**
     * Ajax handler. If the property is passed as component parameter is won't be available here.
     * Use hidden input and extract the value from the request.
     * @return array
     */
    public function onDataLoad()
    {
        $mapId = $this->property('mapId');
        if (!$mapId)
            $mapId = $this->param('mapId');
        if (!$mapId)
            $mapId = request('mapId');
        $mapSlug = $this->property('mapSlug');
        if (!$mapSlug)
            $mapSlug = $this->param('mapSlug');
        if (!$mapSlug)
            $mapSlug = request('mapSlug');
        $itemIds = $this->property('itemIds');
        if (!$itemIds)
            $itemIds = request()->get('item_ids');
        if ($itemIds)
            $itemIds = explode(',', $itemIds);
        if ($mapId || $mapSlug) {
            if ($mapId) {
                $map = Map::where('id', $mapId)->with('image')->first();
            } else {
                $map = Map::where('slug', $mapSlug)->with('image')->first();
                $mapId = $map->id;
            }
            $markers = Position::where('map_id', $mapId);
            if ($itemIds)
                $markers->whereIn('model_id', $itemIds);
            $markers = $markers->get();
            foreach ($markers as $marker) {
                $marker->markerUrl = $this->determineMarkerUrl($marker);
                $marker->popupContent = $this->renderPartial('::popup', ['marker' => $marker]);
            }
            $result = ['status' => 'success', 'map' => $map, 'markers' => $markers];
            $guideId = $this->param('id');
            if ($guideId) {
                $guide = Guide::where('slug', $guideId)->first();
                $result['guide_style'] = $guide->style;
            }
            return $result;
        }
        return ['status' => 'error'];
    }

    /**
     *
     * Renders info box in response to marker's click
     *
     * @return string
     */
    public function onMarkerClicked()
    {
        $id = Request::input('marker_id');
        $marker = Position::where('id', $id)->first();
        $marker->markerUrl = $this->determineMarkerUrl($marker);
        return $this->renderPartial('::popup', ['marker' => $marker]);
    }
}
