/**
 *  Initializes Google Map for Markers Map widget
 */

var markersMap, infoWindow;
var marker = null;
var markers = [];
var gmarkers = [];
var defaultLocaleCode;

// https://sites.google.com/site/gmapsdevelopment/
// https://developers.google.com/maps/documentation/javascript/custom-markers
const iconUrl = "http://maps.google.com/mapfiles/kml/paddle/";

function markersMapInit() {
}

function generateUniqueId() {
    // Math.random should be unique because of its seeding algorithm.
    // Convert it to base 36 (numbers + letters), and grab the first 9 characters
    // after the decimal.
    return (
        '_' +
        Math.random()
            .toString(36)
            .substr(2, 9)
    );
}

function canCreateMarker() {
    const urlParams = new URLSearchParams(window.location.search);
    const typeParam = urlParams.get('type');
    let canCreate = true;
    if (typeParam === 'gps' || typeParam === 'img') {
        mapId = window.location.pathname.split('/').pop();
        canCreate = false;
    }
    return canCreate
}

/**
 * Adds markers to map
 * Markers are expected to be a JSON in data.result
 */
function markersMapAddMarkers(result) {
    if (result == undefined) {
        return;
    }

    if (result.status === 'success') {
        $('#markersmap').empty();
        $('#markersmap').removeClass('leaflet-map-active');
        $('#markersmap').addClass('gmap-map-active');

        markersMap = new google.maps.Map(document.getElementById('markersmap'), {
            center: {lat: parseFloat(result.map.coord_y), lng: parseFloat(result.map.coord_x)},
            zoom: result.map.zoom,
            disableDoubleClickZoom: true
        });

        /**
         * Listen double clicks on map to update latitude (y) and longitude (x) inputs
         */
        markersMap.addListener('dblclick', function (e) {
            var coords = e.latLng;
            if (marker) {
                marker.setPosition(coords);
            } else if (canCreateMarker()) {
                marker = new google.maps.Marker({
                    position: coords,
                    map: markersMap,
                    icon: {
                        url: iconUrl + 'red' + '-circle.png'
                    }
                });
            }

            var activeMarker = getActiveMarker()
            if (!activeMarker && !canCreateMarker()) {
                return;
            }

            //update coordinate inputs
            $('#marker_coord_y').val(coords.lat);
            $('#marker_coord_x').val(coords.lng);
            markers.forEach(function (item) {
                item.active = false;
            })
            if (activeMarker) {
                activeMarker.active = true;
                activeMarker.coord_x = coords.lng;
                activeMarker.coord_y = coords.lat;
            } else {
                markers.push({id: generateUniqueId(), 'active': true, 'coord_x': coords.lng, 'coord_y': coords.lat});
                gmarkers.push(marker);
            }
            updateActiveMarkerProperties()
        });


        markers = result.markers;

        $('#marker_coord_x').val('');
        $('#marker_coord_y').val('');
        $('#marker_title').val('');
        $('#marker_description').val('');
        $('#marker_external_url').val('');
        $('[name$="[marker_title]"]').val('');
        $('[name$="[marker_description]"]').val('');

        marker = null;

        gmarkers = [];
        defaultLocaleCode = result.defaultLocale.code

        for (var i = 0; i < markers.length; i++) {
            let color = 'blu'
            if (markers[i].active) {
                color = 'red'
            }
            var gmMarker = new google.maps.Marker({
                position: {
                    lat: parseFloat(markers[i].coord_y),
                    lng: parseFloat(markers[i].coord_x)
                },
                map: markersMap,
                title: markers[i].title,
                icon: {
                    url: iconUrl + color + '-circle.png'
                }
            });

            gmarkers.push(gmMarker);

            if (markers[i].active) {
                marker = gmMarker;

                updateFieldsForMarker(markers[i]);
            }
        }

        $('#map-form').show();
    }
}

function deleteActiveMarker() {
    var activeMarker = getActiveMarker();
    var idx = -1;
    if ($('#markersmap').hasClass('gmap-map-active') && markers.length > 0) {
        idx = markers.findIndex(x => x.id === activeMarker.id);
    } else if ($('#markersmap').hasClass('leaflet-map-active') && imarkers.length > 0) {
        idx = imarkers.findIndex(x => x.id === activeMarker.id);
    }
    if (idx > -1) {
        if (idx < gmarkers.length) {
            markers.splice(idx, 1);
            gmarkers[idx].setMap(null);
            gmarkers.splice(idx, 1);
            marker = null;
        }
        if (idx < lmarkers.length) {
            imarkers.splice(idx, 1);
            lmarkers[idx].remove();
            lmarkers.splice(idx, 1);
            imarker = null;
        }
        updateFieldsForMarker();
    }
}

function activateMarkerWithId(newActiveMarkerId) {

    for (let i = 0; i < markers.length; i++) {
        if (markers[i].id === parseInt(newActiveMarkerId)) {
            markers[i].active = true;
            marker = gmarkers[i];
            marker.setIcon(iconUrl + 'red' + '-circle.png')

            updateFieldsForMarker(markers[i])
        } else {
            markers[i].active = false;
            gmarkers[i].setIcon(iconUrl + 'blu' + '-circle.png')
        }
    }

    // See leafletmaps.js
    for (let i = 0; i < imarkers.length; i++) {
        if (imarkers[i].id === parseInt(newActiveMarkerId)) {
            imarkers[i].active = true;
            imarker = lmarkers[i];
            imarker.setIcon(redIcon)

            updateFieldsForMarker(imarkers[i])
        } else {
            imarkers[i].active = false;
            lmarkers[i].setIcon(blueIcon)
        }
    }
}

function updateFieldsForMarker(marker) {
    $('*[id^="marker_title"]').val('')
    $('*[id^="marker_description"]').val('')
    $('[data-active-locale]').text(defaultLocaleCode);
    marker ? $('#marker_coord_x').val(marker.coord_x) : $('#marker_coord_x').val('');
    marker ? $('#marker_coord_y').val(marker.coord_y) : $('#marker_coord_y').val('');
    marker ? $('#marker_title').val(marker.title) : $('#marker_title').val('');
    marker ? $('#marker_description').val(marker.description) : $('#marker_description').val('');
    marker ? $('#marker_title_' + defaultLocaleCode).val(marker.title) : $('#marker_title_' + defaultLocaleCode).val('');
    marker ? $('#marker_description_' + defaultLocaleCode).val(marker.description) : $('#marker_description_' + defaultLocaleCode).val('');
    if (marker && marker.translationsEdited) {
        if (marker.translationsEdited.description) {
            marker.translationsEdited.description.forEach(function (t) {
                if (t) {
                    $('#marker_description_' + t.locale).val(t.value)

                    if (t.locale === defaultLocaleCode) {
                        $('#marker_description').val(t.value);
                    }
                }
            });
        }
        if (marker.translationsEdited.title) {
            marker.translationsEdited.title.forEach(function (t) {
                if (t) {
                    $('#marker_title_' + t.locale).val(t.value)

                    if (t.locale === defaultLocaleCode) {
                        $('#marker_title').val(t.value);
                    }
                }
            });
        }
    } else if (marker && marker.translations) {
        marker.translations.forEach(function (t) {
            let attributeData = JSON.parse(t.attribute_data);
            if (attributeData.title) {
                $('#marker_title_' + t.locale).val(attributeData.title)

                if (t.locale === defaultLocaleCode) {
                    $('#marker_title').val(attributeData.title);
                }
            }
            if (attributeData.description) {
                $('#marker_description_' + t.locale).val(attributeData.description)

                if (t.locale === defaultLocaleCode) {
                    $('#marker_description').val(attributeData.description);
                }
            }
        });
    } else {
        $('input[type=hidden]').val('');
    }

    marker ? $('#marker_external_url').val(marker.external_url) : $('#marker_external_url').val('');
    if (!marker) {
        $('[data-type=marker-item].selected').remove();
    }
}

function updateMarkerCoordX(val) {
    if (marker) {
        let newLatlng = new google.maps.LatLng(parseFloat($('#marker_coord_y').val()), parseFloat(val));
        marker.setPosition(newLatlng);
        markersMap.setCenter(newLatlng);
    }

    // See leafletmaps.js
    if (imarker) {
        let newLatlng = [parseFloat($('#marker_coord_y').val()), parseFloat(val)]
        imarker.setLatLng(newLatlng);
        // imarkersMap.setView(newLatlng, 0);
    }
}

function updateMarkerCoordY(val) {
    if (marker) {
        let newLatlng = new google.maps.LatLng(parseFloat(val), parseFloat($('#marker_coord_x').val()));
        marker.setPosition(newLatlng);
        markersMap.setCenter(newLatlng);
    }

    // See leafletmaps.js
    if (imarker) {
        let newLatlng = [parseFloat(val), parseFloat($('#marker_coord_x').val())]
        imarker.setLatLng(newLatlng);
        // imarkersMap.setView(newLatlng, 0);
    }
}

function updateMarkerTitle(val) {
    if (marker) {
        marker.setTitle(val);
    }

    // See leafletmaps.js
    if (imarker) {
        imarker.getElement().setAttribute('title', val);
    }
}

function getActiveMarker() {
    var markersArray;
    if ($('#markersmap').hasClass('gmap-map-active') && markers.length > 0) {
        markersArray = markers;
    } else if ($('#markersmap').hasClass('leaflet-map-active') && imarkers.length > 0) {
        markersArray = imarkers;
    }
    if (markersArray) {
        for (let i = 0; i < markersArray.length; i++) {
            if (markersArray[i].active) {
                if (i < gmarkers.length) {
                    marker = gmarkers[i];
                }
                if (i < lmarkers.length) {
                    imarker = lmarkers[i];
                }

                return markersArray[i];
            }
        }
    }
    return null;
}

function updateActiveMarkerProperties() {
    var activeMarker = getActiveMarker();
    if (activeMarker) {
        activeMarker.external_url = $('#marker_external_url').val();
        activeMarker.coord_x = $('#marker_coord_x').val();
        activeMarker.coord_y = $('#marker_coord_y').val();

        var translations = {}
        var markerTitleTranslations = []
        $('[name$="[marker_title]"]').each(function (index, item) {
            var locale = $(this).attr('data-locale-value')
            var val = $(this).val();
            if (locale) {
                markerTitleTranslations.push({locale: locale, value: val})
            }
            if (!locale || locale === defaultLocaleCode) {
                activeMarker.title = val;
            }
        })
        translations.title = markerTitleTranslations

        var markerDescriptionTranslations = []
        $('[name$="[marker_description]"]').each(function (index, item) {
            var locale = $(this).attr('data-locale-value')
            var val = $(this).val();
            if (locale) {
                markerDescriptionTranslations.push({
                    locale: locale,
                    value: val
                })
            }
            if (!locale || locale === defaultLocaleCode) {
                activeMarker.description = val;
            }
        })
        translations.description = markerDescriptionTranslations

        activeMarker.translationsEdited = translations;

        $('[data-id="' + activeMarker.id + '"]').find('.item-title').text(markerTitleTranslations.length > 0 ? markerTitleTranslations.find(x => x.locale === defaultLocaleCode).value : activeMarker.title);
    }


}
