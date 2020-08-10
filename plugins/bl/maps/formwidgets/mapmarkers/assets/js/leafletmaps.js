var imarkersMap;
var imarker = null;
var imarkers = [];
var lmarkers = [];

/**
 * Adds markers to map
 * Markers are expected to be a JSON in data.result
 */
function markersMapImageAddMarkers(result) {
    if (result == undefined) {
        return;
    }

    if (result.status === 'success') {
        imarkers = result.markers;

        $('#markersmap').empty();
        $('#markersmap').removeClass();
        $('#markersmap').attr('style', 'width: auto; height: 100%;')
        $('#markersmap').removeClass('gmap-map-active');
        $('#markersmap').addClass('leaflet-map-active');


        if (imarkersMap) {
            imarkersMap.off();
            imarkersMap.remove();
        }

        $('#map-form').show();

        imarkersMap = L.map('markersmap', {
            crs: L.CRS.Simple,
            minZoom: 1,
            maxZoom: 4,
            center: [0, 0],
            zoom: result.map.zoom ? result.map.zoom : 1,
            doubleClickZoom: false
        });

        var img = new Image();
        img.onload = function () {
            // https://kempe.net/blog/2014/06/14/leaflet-pan-zoom-image.html
            // calculate the edges of the image, in coordinate space
            var southWest = imarkersMap.unproject([0, this.height], imarkersMap.getMaxZoom() - 1);
            var northEast = imarkersMap.unproject([this.width, 0], imarkersMap.getMaxZoom() - 1);
            var bounds = new L.LatLngBounds(southWest, northEast);

            // igorbel: difference is here was full_path, now path. See also Map.php.
            var image = L.imageOverlay(result.map.image.path, bounds).addTo(imarkersMap);

            imarkersMap.setMaxBounds(bounds);
        }
        // igorbel: difference is here was full_path, now path. See also Map.php.
        img.src = result.map.image.path;

        defaultLocaleCode = result.defaultLocale.code;

        /**
         * Listen double clicks on map to update latitude (y) and longitude (x) inputs
         */
        imarkersMap.on('dblclick', function (e) {
            var coords = e.latlng;
            if (imarker) {
                imarker.setLatLng(coords);
            } else if (canCreateMarker()) {
                imarker = L.marker(coords, {
                        icon: redIcon
                    }
                ).addTo(imarkersMap);
            }

            var activeMarker = getActiveMarker()
            if (!activeMarker && !canCreateMarker()) {
                return;
            }

            //update coordinate inputs
            $('#marker_coord_y').val(coords.lat);
            $('#marker_coord_x').val(coords.lng);

            imarkers.forEach(function (item) {
                item.active = false;
            })
            if (activeMarker) {
                activeMarker.active = true;
                activeMarker.coord_x = coords.lng;
                activeMarker.coord_y = coords.lat;
            } else {
                imarkers.push({id: generateUniqueId(), 'active': true, 'coord_x': coords.lng, 'coord_y': coords.lat});
                lmarkers.push(imarker);
            }
        });

        $('#marker_coord_x').val('');
        $('#marker_coord_y').val('');
        $('#marker_title').val('');
        $('#marker_description').val('');
        $('#marker_external_url').val('');
        $('[name$="[marker_title]"]').val('');
        $('[name$="[marker_description]"]').val('');

        imarker = null;

        lmarkers = [];

        for (var i = 0; i < imarkers.length; i++) {
            let color = 'blu'
            if (imarkers[i].active) {
                color = 'red'
            }

            var lMarker = L.marker([parseFloat(imarkers[i].coord_y), parseFloat(imarkers[i].coord_x)],
                {
                    title: imarkers[i].title,
                    icon: imarkers[i].active ? redIcon : blueIcon
                }
            ).addTo(imarkersMap);

            lmarkers.push(lMarker);

            if (imarkers[i].active) {
                imarker = lMarker;

                $('#marker_coord_x').val(imarkers[i].coord_x);
                $('#marker_coord_y').val(imarkers[i].coord_y);

                $('#marker_title').val(imarkers[i].title);
                $('#marker_description').val(imarkers[i].description);
                $('#marker_title_'+result.defaultLocale.code).val(imarkers[i].title);
                $('#marker_description_'+result.defaultLocale.code).val(imarkers[i].description);
                if (imarkers[i].translations) {
                    imarkers[i].translations.forEach(function (t) {
                        let attributeData = JSON.parse(t.attribute_data);
                        if (attributeData.title) {
                            $('#marker_title_' + t.locale).val(attributeData.title)

                            if (t.locale === result.defaultLocale.code) {
                                $('#marker_title').val(attributeData.title);
                            }
                        }
                        if (attributeData.description) {
                            $('#marker_description_' + t.locale).val(attributeData.description)

                            if (t.locale === result.defaultLocale.code) {
                                $('#marker_description').val(attributeData.description);
                            }
                        }
                    });
                }

                $('#marker_external_url').val(imarkers[i].external_url);
            }
        }

    }
}
