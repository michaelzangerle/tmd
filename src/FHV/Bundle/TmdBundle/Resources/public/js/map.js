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

            this.map = new google.maps.Map(document.getElementById(selector), mapOptions);
        },

        /**
         * Draws all segments of a track
         * @param track represents a track with segments and points
         */
        drawTrack: function (track) {
            if (!!track.segments) {
                track.segments.forEach(function (segment) {
                    this.drawSegment(segment);
                }.bind(this));
            }
        },

        /**
         * Draws a segment onto the map
         * @param segment
         */
        drawSegment: function (segment) {
            if (!!segment.trackpoints && segment.trackpoints.length >= 2) {
                var coordinates = this.createCoordinates(segment.trackpoints);
                var segmentPath = new google.maps.Polyline({
                    path: coordinates,
                    geodesic: true,
                    strokeColor: '#FF0000',
                    strokeOpacity: 1.0,
                    strokeWeight: 2
                });

                segmentPath.setMap(this.map);
            }
        },

        /**
         * Processes an array of trackpoints
         * @param trackPoints Array
         * @return LatLng[]
         */
        createCoordinates: function (trackPoints) {
            var result = [], last;

            if(!!this.lastCoordiante) {
                result.push(this.lastCoordiante);
            }

            trackPoints.forEach(function (tp) {
                result.push(new google.maps.LatLng(tp.latitude, tp.longitude))
            }.bind(this));

            last = trackPoints[trackPoints.length -1];
            this.lastCoordiante = new google.maps.LatLng(last.latitude, last.longitude);
            return result;
        }

    };
});
