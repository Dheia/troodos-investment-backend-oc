/**
 * Initializes map
 */
var markersMap;
var markersMapInfoBox, infoWindow;
var imarkersMap;
markers = [];
imarkers = [];
gmarkers = [];
const iconUrl = "http://maps.google.com/mapfiles/kml/paddle/";
const iconUrl2 = "http://maps.google.com/mapfiles/ms/micons/";

function mapComponentInit()
{
    //load settings and markers
    $(this).request('onDataLoad', {
        data: {mapId: $('#mapId').val()},
        success: function (data) {
            addMarkersToMap(data);
            addMarkersToTable(data);
        }
    });
}


/**
 * Adds markers to map
 * Markers are expected to be a JSON in data.markers
 */
function addMarkersToMap(data)
{
    if (data == undefined) {
        return;
    }

    if (data.map.type === 'gps') {
        createGoogleMap(data)
    } else if (data.map.type === 'img') {
        createLeafletMap(data)
    }
}

function createGoogleMap(data)
{
    // init map with settings
    markersMap = new google.maps.Map(document.getElementById('mapcomponent'), {
        center: {
            lat: parseFloat(data.map.coord_y),
            lng: parseFloat(data.map.coord_x)
        },
        zoom: parseInt(data.map.zoom)
    });

    infoWindow = new google.maps.InfoWindow({content:''});
    // Try HTML5 geolocation.
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };

            // infoWindow.setPosition(pos);
            // infoWindow.setContent('Location found.');
            // infoWindow.open(map);
            // map.setCenter(pos);
            var userLocationMarker = new google.maps.Marker({
                position: pos,
                map: markersMap,
                icon: {
                    url: iconUrl2 + 'man' + '.png'
                }
            });
        }, function () {
            handleLocationError(true, infoWindow, markersMap.getCenter());
        });
    } else {
        // Browser doesn't support Geolocation
        handleLocationError(false, infoWindow, markersMap.getCenter());
    }

    // init infobox
    markersMapInfoBox = new google.maps.InfoWindow({
        content: ''
    });

    markers = data.markers;
    for (var i = 0; i < data.markers.length; i++) {
        var gMarker = new google.maps.Marker({
            position: {
                lat: parseFloat(data.markers[i].coord_y),
                lng: parseFloat(data.markers[i].coord_x)
            },
            map: markersMap,
            title: data.markers[i].title,
            icon: {
                url: iconUrl + 'blu' + '-circle.png'
            }
        });
        gMarker.marker_id = data.markers[i].id;
        gmarkers.push(gMarker);
        //bind marker click to show info box
        gMarker.addListener('click', function () {
            var clickedMarker = this;
            $(this).request('onMarkerClicked', {
                data: {marker_id: clickedMarker.marker_id},
                success: function (data) {
                    markersMapInfoBox.setContent(data.result);
                    markersMapInfoBox.open(markersMap, clickedMarker);
                }
            });
        });
    }
}

function handleLocationError(browserHasGeolocation, infoWindow, pos)
{
    infoWindow.setPosition(pos);
    infoWindow.setContent(
        browserHasGeolocation ?
                        'Error: The Geolocation service failed.' :
        'Error: Your browser doesn\'t support geolocation.'
    );
    infoWindow.open(markersMap);
}


function createLeafletMap(data)
{
    imarkers = data.markers;

    if (imarkersMap) {
        imarkersMap.off();
        imarkersMap.remove();
    }

    imarkersMap = L.map('mapcomponent', {
        crs: L.CRS.Simple,
        minZoom: 1,
        maxZoom: 4,
        center: [0, 0],
        zoom: 1,
        doubleClickZoom: false
    });

    var img = new Image();
    img.onload = function () {
        // https://kempe.net/blog/2014/06/14/leaflet-pan-zoom-image.html
        // calculate the edges of the image, in coordinate space
        var southWest = imarkersMap.unproject([0, this.height], imarkersMap.getMaxZoom() - 1);
        var northEast = imarkersMap.unproject([this.width, 0], imarkersMap.getMaxZoom() - 1);
        var bounds = new L.LatLngBounds(southWest, northEast);
        var image = L.imageOverlay(data.map.image.full_path, bounds).addTo(imarkersMap);
        imarkersMap.setMaxBounds(bounds);
    }
    img.src = data.map.image.full_path;


    lmarkers = [];

    for (var i = 0; i < imarkers.length; i++) {
        let color = 'blu'
        if (imarkers[i].active) {
            color = 'red'
        }

        var lMarker = L.marker(
            [parseFloat(imarkers[i].coord_y), parseFloat(imarkers[i].coord_x)],
            {
                title: imarkers[i].title,
                icon: imarkers[i].active ? redIcon : blueIcon
            }
        ).addTo(imarkersMap)

        lMarker.bindPopup(imarkers[i].popupContent);

        lmarkers.push(lMarker);

        if (imarkers[i].active) {
            imarker = lMarker;
        }
    }
}

function activateMarkerWithId(newActiveMarkerId)
{

    for (let i = 0; i < markers.length; i++) {
        if (markers[i].id === parseInt(newActiveMarkerId)) {
            markers[i].active = true;
            marker = gmarkers[i];
            marker.setIcon(iconUrl + 'red' + '-circle.png')
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
        } else {
            imarkers[i].active = false;
            lmarkers[i].setIcon(blueIcon)
        }
    }
}

function addMarkersToTable(data)
{
    if (data == undefined) {
        return;
    }
    for (var i = 0; i < data.markers.length; i++) {
        $tr = $('<tr></tr>')
        $td = $('<td></td>')
        $markerItem = $('<a class="marker-item"></a>');
        $markerItem.text(data.markers[i].title)
        $markerItem.attr('data-id', data.markers[i].id)
        $markerItem.on('click', function (e) {
            e.preventDefault();
            $('.marker-item').each(function (idx) {
                $(this).parent().css('background-color', '');
            })
            $(this).parent().css('background-color', data.guide_style['accent_color']);
            activateMarkerWithId($(this).attr('data-id'));
        })
        $td.append($markerItem);
        $tr.append($td);
        $('#tablebody').append($tr);
    }
}

