// Stub function to avoid google maps init error. We load google maps scripts and leaflet scripts together.
// See: plugins/bl/maps/formwidgets/MapMarkers.php > loadAssets()
function markerCoordsMapInit() {

}

function imageMapInit() {
    var zoom = parseInt($('#Form-field-Map-zoom').val());
    var imagePath = $('#image-path').val();
    if (!imagePath) {
        imagePath = $('#Form-relationImageViewForm-field-MediaItem-full_path-group .form-control').text().trim();
    }
    if (imagePath) {
        var imarkersMap = L.map('marker-coords-map', {
            crs: L.CRS.Simple,
            minZoom: 1,
            maxZoom: 4,
            center: [0, 0],
            zoom: isNaN(zoom) ? 1 : zoom,
            doubleClickZoom: false
        });


        var img = new Image();
        img.onload = function () {
            // https://kempe.net/blog/2014/06/14/leaflet-pan-zoom-image.html
            // calculate the edges of the image, in coordinate space
            var southWest = imarkersMap.unproject([0, this.height], imarkersMap.getMaxZoom() - 1);
            var northEast = imarkersMap.unproject([this.width, 0], imarkersMap.getMaxZoom() - 1);
            var bounds = new L.LatLngBounds(southWest, northEast);
            var image = L.imageOverlay(imagePath, bounds).addTo(imarkersMap);
            imarkersMap.setMaxBounds(bounds);
        }
        img.src = imagePath;


        imarkersMap.on('zoomend', function (e) {
            var currZoom = imarkersMap.getZoom();
            $('#Form-field-Map-zoom').val(currZoom);
        });
    }
}

$(function () {
    imageMapInit();

    var fullPathEl = $('#MapsController-create-RelationController-image-view').get(0);

    var observer = new MutationObserver(function (mutations) {
        mutations.forEach(function (mutation) {
            var fullPath = $('#Form-relationImageViewForm-field-MediaItem-full_path-group .form-control').text();
            if (fullPath) {
                imageMapInit();
            }
        })
    })
    if (fullPathEl) {
        observer.observe(fullPathEl, {
            childList: true,
            attributes: true,
            characterData: true
        })
    }
});
