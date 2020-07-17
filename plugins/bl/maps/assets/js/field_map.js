/**
 *  Initializes Google Map for MarkerCoords partial
 */

function markerCoordsMapInit()
{
    var markerCoordsMap;
    var marker = null;

    var zoom = parseInt($('#Form-field-Map-zoom').val());
    markerCoordsMap = new google.maps.Map(document.getElementById('marker-coords-map'), {
        center: {lat: 20, lng: 0},
        zoom: zoom ? zoom : 2,
        disableDoubleClickZoom: true
    });

  //check if there'are existing values, then create marker
    var latitude = $('#Form-field-Map-coord_y').val();
    var longitude = $('#Form-field-Map-coord_x').val();
    if (latitude && longitude) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);
        marker = new google.maps.Marker({
            position: {
                lat: latitude,
                lng: longitude
            },
            map: markerCoordsMap,
            icon: {
                url: "http://maps.google.com/mapfiles/arrow.png"
            }

        });
      //center map to marker
        markerCoordsMap.setCenter({
            lat: latitude,
            lng: longitude
        });
    }

  /**
   * Listen double clicks on map to update latitude and longitude inputs
   */
    markerCoordsMap.addListener('dblclick', function (e) {
        var coords = e.latLng;
        if (marker) {
            marker.setPosition(coords);
        } else {
            marker = new google.maps.Marker({
                position: coords,
                map: markerCoordsMap,
                icon: {
                    url: "http://maps.google.com/mapfiles/arrow.png"
                }

            });
        }

      //update coordinate inputs
        $('#Form-field-Map-coord_y').val(coords.lat);
        $('#Form-field-Map-coord_x').val(coords.lng);
    });
    // Setup the click event listener for Zoom Increase:
    google.maps.event.addListener(markerCoordsMap, 'zoom_changed', function () {
        zoom = markerCoordsMap.getZoom();
        $('#Form-field-Map-zoom').val(zoom);
    });

}
