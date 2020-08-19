<?php return [
    'plugin' => [
        'name' => 'Maps',
        'description' => '',
        'map_widget_name' => 'Markers Map',
        'map_widget_description' => 'Widget to display map with all markers on it in the Backend',
        'map_component_label' => 'Map Component',
        'map_component_description' => 'Map Component',
    ],
    'maps' => [
        'name' => 'Map',
        'names' => 'Maps',
        'gps' => 'GPS',
        'img' => 'Image',
        'item_ids' => 'Item Ids',
        'save_marker' => 'Save marker related data',
        'delete_marker' => 'Remove this position from map',
        'delete_marker_confirm' => 'Are you sure?',
        'other_markers' => 'Other markers on the map. Click to edit position.',
        'select_map' => 'Select a map',
        'parent_not_ready' => 'You need to create the item first before you can associate it with a map. Create the item and refresh to see this functionality.',
        'no_markers_found' => 'No markers',
        'data_saved' => 'Data saved',
        'data_deleted' => 'Data deleted',
        'cant_create' => 'The marker can\'t be created in this mode',
        'external_url_validation_failed' => 'External Url: validation failed',
        'marker_id_is_required' => 'Required selected marker',
        'columns' => [
            'name' => 'Map name',
            'type' => 'Map type'
        ],
        'field' => [
            'name' => 'Map name',
            'slug' => 'Slug',
            'namecomment' => 'If you associate this map with one of your guides, this is the map name that guide users will see',
            'gpsmap' => 'Define map - double click to set map center',
            'gpsmapcomment' => 'Define the area that your map should cover. Zoom and drag the map to your desired location. You must double click on the map to set the map center and retain the map location when saving the map. ',
            'title' => 'Title',
            'description' => 'Description',
            'url' => 'Url',
            'external_url' => 'External Url',
            'coords' => 'Coordinates',
            'coord_x_gps' => 'Longitude',
            'coord_y_gps' => 'Latitude',
            'zoom' => 'Zoom level',
            'coord_x_img' => 'x-coordinate',
            'coord_y_img' => 'y-coordinate',
        ],
        'menu' => [
            'maps' => 'Maps',
        ],
        'fields' => [
            'image' => 'Image to use to represent the map:',
            'image_comment' => 'You will not be able to change the image once the map is saved.',
        ],
        'tabs' => [
            'map_details' => 'Map Details',
            'map_items' => 'Items on the map'
        ]
    ],

];
