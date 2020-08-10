+function ($) {
    "use strict";

    if ($.oc.mapMarkers === undefined)
        $.oc.mapMarkers = {}

    var Base = $.oc.foundation.base,
        BaseProto = Base.prototype

    // MAP MARKERS CLASS DEFINITION
    // ============================

    var MapMarkers = function (element, options) {
        this.$el = $(element)
        this.$form = this.$el.closest('form')

        // State properties
        this.selectTimer = null
        this.dblTouchTimer = null
        this.dblTouchFlag = null

        this.options = options
        Base.call(this)

        //
        // Initialization
        //

        this.init()
    }

    MapMarkers.prototype = Object.create(BaseProto)
    MapMarkers.prototype.constructor = MapMarkers

    MapMarkers.prototype.dispose = function () {
        this.unregisterHandlers()
        this.clearDblTouchTimer()
        this.clearSelectTimer()

        this.$el.removeData('oc.mapMarkers')
        this.$el = null
        this.$form = null

        BaseProto.dispose.call(this)
    }

    // MAP MARKERS INTERNAL METHODS
    // ============================

    MapMarkers.prototype.init = function () {
        this.registerHandlers()

        // When we load the widget for the Maps page of MapsController we don't show the list of all maps.
        // Extract mapId from the url.
        const urlParams = new URLSearchParams(window.location.search);
        const typeParam = urlParams.get('type');
        if (typeParam === 'gps' || typeParam === 'img') {
            var mapId = window.location.pathname.split('/').pop();
            $('#map-list-c').hide();
            this.loadSelectedMap(mapId);
        }
    }

    MapMarkers.prototype.registerHandlers = function () {
        this.$el.on('change', '[data-control="map_list"]', this.proxy(this.onMapChanged))
        this.$el.on('change', '[data-control="marker_coord_x"]', this.proxy(this.onMarkerCoordXChanged))
        this.$el.on('change', '[data-control="marker_coord_y"]', this.proxy(this.onMarkerCoordYChanged))
        this.$el.on('change', '[data-control="marker_title"]', this.proxy(this.onMarkerTitleChanged))
        this.$el.on('change', '[data-control="marker_description"]', this.proxy(this.onMarkerDescriptionChanged))
        this.$el.on('change', '[data-control="marker_external_url"]', this.proxy(this.onMarkerExternalUrlChanged))
        this.$el.on('click.command', '[data-command]', this.proxy(this.onCommandClick))

        // Touch devices use double-tap for the navigation and single tap for selecting.
        // Another option is checkboxes visible only on touch devices, but this approach
        // will require more significant changes in the code for the touch device support.
        this.$el.on('click.item', '[data-type="marker-item"]', this.proxy(this.onItemClick))
        this.$el.on('touchend', '[data-type="marker-item"]', this.proxy(this.onItemTouch))
    }

    MapMarkers.prototype.unregisterHandlers = function () {
        this.$el.off('change', '[data-control="map_list"]', this.proxy(this.onMapChanged))
        this.$el.off('change', '[data-control="marker_coord_x"]', this.proxy(this.onMarkerCoordXChanged))
        this.$el.off('change', '[data-control="marker_coord_y"]', this.proxy(this.onMarkerCoordYChanged))
        this.$el.off('change', '[data-control="marker_title"]', this.proxy(this.onMarkerTitleChanged))
        this.$el.off('change', '[data-control="marker_description"]', this.proxy(this.onMarkerDescriptionChanged))
        this.$el.off('change', '[data-control="marker_external_url"]', this.proxy(this.onMarkerExternalUrlChanged))
        this.$el.off('click.command', this.proxy(this.onCommandClick))

        this.$el.off('click.item', this.proxy(this.onItemClick))
        this.$el.off('touchend', '[data-type="marker-item"]', this.proxy(this.onItemTouch))

    }

    MapMarkers.prototype.onCommandClick = function (ev) {
        var command = $(ev.currentTarget).data('command')

        switch (command) {
            case 'save-marker':
                this.saveMarker()
                break;
            case 'delete-marker':
                var r = confirm("Are you sure?");
                if (r === true) {
                    this.deleteMarker()
                }
                break;
        }

        return false
    }

    MapMarkers.prototype.onItemClick = function (ev) {

        this.selectItem(ev.currentTarget, ev.shiftKey)
    }

    MapMarkers.prototype.onItemTouch = function (ev) {
        // The 'click' event is triggered after 'touchend',
        // so we can prevent handling it.
        ev.preventDefault()
        ev.stopPropagation()

        if (this.dblTouchFlag) {
            this.dblTouchFlag = null
        } else {
            this.onItemClick(ev)
            this.dblTouchFlag = true
        }

        this.clearDblTouchTimer()

        this.dblTouchTimer = setTimeout(this.proxy(this.clearDblTouchFlag), 300)
    }

    MapMarkers.prototype.clearDblTouchTimer = function () {
        if (this.dblTouchTimer === null)
            return

        clearTimeout(this.dblTouchTimer)
        this.dblTouchTimer = null
    }

    MapMarkers.prototype.clearSelectTimer = function () {
        if (this.selectTimer === null)
            return

        clearTimeout(this.selectTimer)
        this.selectTimer = null
    }

    MapMarkers.prototype.selectItem = function (node, expandSelection) {
        if (!expandSelection) {
            var items = this.$el.get(0).querySelectorAll('[data-type="marker-item"].selected')

            // The class attribute is used only for selecting, it's safe to clear it
            for (var i = 0, len = items.length; i < len; i++) {
                items[i].setAttribute('class', '')
            }

            node.setAttribute('class', 'selected')
        } else {
            if (node.getAttribute('class') == 'selected')
                node.setAttribute('class', '')
            else
                node.setAttribute('class', 'selected')
        }

        node.focus()

        this.clearSelectTimer()

        // if (this.isPreviewSidebarVisible()) {
        // Use the timeout to prevent too many AJAX requests
        // when the selection changes too quickly (with the keyboard arrows)
        // this.selectTimer = setTimeout(this.proxy(this.updateSidebarPreview), 100)
        // }
        var selectedItems = this.$el.get(0).querySelectorAll('[data-type="marker-item"].selected');

        if (selectedItems.length === 1) {
            var item = selectedItems[0],
                markerId = item.getAttribute('data-id');
            activateMarkerWithId(markerId);
        }

    }

    MapMarkers.prototype.saveMarker = function () {
        if (this.saveMarkerAjax) {
            try {
                this.saveMarkerAjax.abort()
            } catch (e) {
            }
            this.saveMarkerAjax = null
        }

        updateActiveMarkerProperties()
        var activeMarker = getActiveMarker();

        var $mapList = this.$el.find('#map_list')
        var mapId = $mapList.val()

        // When we load the widget for the Maps page of MapsController we don't show the list of all maps.
        // Extract mapId from the url.
        const urlParams = new URLSearchParams(window.location.search);
        const typeParam = urlParams.get('type');
        let canCreate = 1;
        if (typeParam === 'gps' || typeParam === 'img') {
            mapId = window.location.pathname.split('/').pop();
            canCreate = 0;
        }

        var data = {
            map_id: mapId,
            marker_id: activeMarker.id,
            can_create: canCreate,
            title: activeMarker.title,
            description: activeMarker.description,
            external_url: activeMarker.external_url,
            coord_x: activeMarker.coord_x,
            coord_y: activeMarker.coord_y,
            translations: activeMarker.translationsEdited
        }

        $.oc.stripeLoadIndicator.show()
        this.saveMarkerAjax = this.$form.request(this.options.alias + '::onSaveMarker', {
            data: data
        })
            .always(function () {
                $.oc.stripeLoadIndicator.hide()
            })
            .done(this.proxy(this.afterSaveMarker))
            .always(this.proxy(this.releaseSaveMarkerAjax))
    }

    MapMarkers.prototype.deleteMarker = function () {
        if (this.deleteMarkerAjax) {
            try {
                this.deleteMarkerAjax.abort()
            } catch (e) {
            }
            this.deleteMarkerAjax = null
        }

        var activeMarker = getActiveMarker();
        if (activeMarker) {
            var $mapList = this.$el.find('#map_list')
            var mapId = $mapList.val()

            // When we load the widget for the Maps page of MapsController we don't show the list of all maps.
            // Extract mapId from the url.
            const urlParams = new URLSearchParams(window.location.search);
            const typeParam = urlParams.get('type');
            if (typeParam === 'gps' || typeParam === 'img') {
                mapId = window.location.pathname.split('/').pop();
            }

            var data = {
                map_id: mapId,
                marker_id: activeMarker.id,
            }

            $.oc.stripeLoadIndicator.show()
            this.deleteMarkerAjax = this.$form.request(this.options.alias + '::onDeleteMarker', {
                data: data
            })
                .always(function () {
                    $.oc.stripeLoadIndicator.hide()
                })
                .done(this.proxy(this.afterDeleteMarker))
                .always(this.proxy(this.releaseDeleteMarkerAjax))
        }
    }

    MapMarkers.prototype.afterSaveMarker = function (response) {
        if (response.status === 'success') {
            var activeMarker = getActiveMarker();
            activeMarker.id = response.marker_id;

            $('#marker-list').html(response['#marker-list']);

            $.oc.flashMsg({
                text: response.message,
                'class': 'success',
                'interval': 3
            });
        } else if (response.status === 'error') {
            $.oc.flashMsg({
                text: response.message,
                'class': 'error',
                'interval': 3
            });
        }
    }

    MapMarkers.prototype.afterDeleteMarker = function (response) {
        if (response.status === 'success') {
            deleteActiveMarker();

            $.oc.flashMsg({
                text: response.message,
                'class': 'success',
                'interval': 3
            });
        } else if (response.status === 'error') {
            $.oc.flashMsg({
                text: response.message,
                'class': 'error',
                'interval': 3
            });
        }
    }

    MapMarkers.prototype.onMapChanged = function (ev) {
        var $target = $(ev.target);
        if ($target.val()) {
            this.loadSelectedMap($target.val());
        }
    }

    MapMarkers.prototype.onMarkerCoordXChanged = function (ev) {
        var $target = $(ev.target);
        updateMarkerCoordX($target.val());
        updateActiveMarkerProperties()
    }

    MapMarkers.prototype.onMarkerCoordYChanged = function (ev) {
        var $target = $(ev.target);
        updateMarkerCoordY($target.val());
        updateActiveMarkerProperties()
    }

    MapMarkers.prototype.onMarkerTitleChanged = function (ev) {
        var $target = $(ev.target);
        updateMarkerTitle($target.val());
        updateActiveMarkerProperties()
    }

    MapMarkers.prototype.onMarkerDescriptionChanged = function (ev) {
        var $target = $(ev.target);
        updateActiveMarkerProperties()
    }

    MapMarkers.prototype.onMarkerExternalUrlChanged = function (ev) {
        var $target = $(ev.target);
        updateActiveMarkerProperties()
    }

    MapMarkers.prototype.loadSelectedMap = function (mapId) {
        if (this.selectedMapAjax) {
            try {
                this.selectedMapAjax.abort()
            } catch (e) {
            }
            this.selectedMapAjax = null
        }

        var data = {
            map_id: mapId
        }

        this.selectedMapAjax = this.$form.request(this.options.alias + '::onMarkersLoad', {
            data: data
        })
            .done(this.proxy(this.showMap))
            .always(this.proxy(this.releaseSelectedMapAjax))
    }

    MapMarkers.prototype.showMap = function (response) {
        if (response == undefined) {
            return;
        }

        if (markersMap) {
            google.maps.event.clearInstanceListeners(markersMap);
        }
        if (imarkersMap) {
            imarkersMap.off('dblclick');
        }

        if (response.map) {
            if (response.map.type === 'gps') {
                markersMapAddMarkers(response);
            } else if (response.map.type === 'img') {
                markersMapImageAddMarkers(response);
            }
        }

        $('#marker-list').html(response['#marker-list'])
    }

    MapMarkers.prototype.releaseSelectedMapAjax = function () {
        this.selectedMapAjax = null
    }

    MapMarkers.prototype.releaseSaveMarkerAjax = function () {
        this.saveMarkerAjax = null
    }

    MapMarkers.prototype.releaseDeleteMarkerAjax = function () {
        this.deleteMarkerAjax = null
    }

    // MAP MARKERS PLUGIN DEFINITION
    // ============================

    MapMarkers.DEFAULTS = {
        url: window.location,
        alias: '',
    }

    var old = $.fn.mapMarkers

    $.fn.mapMarkers = function (option) {
        var args = Array.prototype.slice.call(arguments, 1),
            result = undefined

        this.each(function () {
            var $this = $(this)
            var data = $this.data('oc.mapMarkers')
            var options = $.extend({}, MapMarkers.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.mapMarkers', (data = new MapMarkers(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : this
    }

    $.fn.mapMarkers.Constructor = MapMarkers

    // MAP MARKERS NO CONFLICT
    // =================

    $.fn.mapMarkers.noConflict = function () {
        $.fn.mapMarkers = old
        return this
    }

    // MAP MARKERS DATA-API
    // ===============

    $(document).on('render', function () {
        $('div[data-control=map-markers]').mapMarkers()
    })

}(window.jQuery);
