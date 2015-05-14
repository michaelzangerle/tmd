define(['async!//maps.googleapis.com/maps/api/js?v=3.exp'], function () {

    'use strict';

    return {

        /**
         * Will initialize the map component
         * @param selector for the map container
         */
        initialize: function (selector) {
            var mapOptions = {
                zoom: 10,
                center: new google.maps.LatLng(47.185203, 10.0249882)

            };

            this.bounds = new google.maps.LatLngBounds();
            this.map = new google.maps.Map(document.getElementById(selector), mapOptions);
        },

        /**
         * Draws all segments of a track
         * @param track represents a track with segments and points
         */
        drawTrack: function (track) {
            if (!!track.segments) {
                track.segments.forEach(function (segment) {
                    this.drawSegment(segment, false);
                }.bind(this));

                this.map.fitBounds(this.bounds);
            }
        },

        /**
         * Draws a segment onto the map
         * @param segment
         */
        drawSegment: function (segment) {
            if (!!segment.trackpoints && segment.trackpoints.length >= 2) {

                var color = this.getColorByType(segment.result.transport_type),
                    lineSymbol = {
                        path: google.maps.SymbolPath.CIRCLE,
                        strokeColor: color,
                        strokeWeight: 4
                    },

                    coordinates = this.createCoordinates(segment.trackpoints),
                    segmentPath = new google.maps.Polyline({
                        path: coordinates,
                        geodesic: true,
                        strokeColor: color,
                        strokeOpacity: 1.0,
                        strokeWeight: 2,
                        icons: [{
                            icon: lineSymbol,
                            offset: '100%'
                        }, {
                            icon: lineSymbol,
                            offset: '0%'
                        }]
                    });

                segmentPath.setMap(this.map);
            }
        },

        /**
         * Gets color for a transport mode type
         * @param type
         */
        getColorByType: function(type){
            switch(type){
                case 'drive':
                    return 'black';
                case 'bus':
                    return 'yellow';
                case 'train':
                    return 'red';
                case 'bike':
                    return 'green';
                case 'walk':
                    return 'brown';
                default:
                    return 'pink';
            }
        },

        /**
         * Processes an array of trackpoints
         * @param trackPoints Array
         * @return LatLng[]
         */
        createCoordinates: function (trackPoints) {
            var result = [], coordinate = null;

            if (!!this.lastCoordinate) {
                result.push(this.lastCoordinate);
            }

            trackPoints.forEach(function (tp) {
                coordinate = new google.maps.LatLng(tp.latitude, tp.longitude);
                result.push(coordinate);
                this.bounds.extend(coordinate);
            }.bind(this));

            this.lastCoordinate = coordinate;
            return result;
        }
    };
});
