define(['underscore', 'async!//maps.googleapis.com/maps/api/js?v=3.exp'], function (_) {

    // TODO extract constants
    // TODO get config from server

    'use strict';

    var transport_types = ['drive', 'walk', 'bus', 'train', 'bike'],
        templates = {
            segment: function () {
                return [
                    '<div class="modal-dialog" data-segment-id="<%= segment.id%>" data-result-id="<%= segment.result.id%>">',
                    '   <div class="modal-content">',
                    '       <div class="modal-header m-bottom-30">',
                    '            <button type="button" class="close" data-dismiss="modal" aria-label="Close">',
                    '                <span aria-hidden="true">&times;</span>',
                    '            </button>',
                    '            <h3 class="modal-title">Segment #<%= segment.id%></h3>',
                    '        </div>',
                    '        <div class="modal-body">',
                    '            <div class="grid-row m-bottom-30">',
                    '                <h4>Details</h4>',
                    '                <p><strong>Distance:</strong> <%= segment.distance %> meters</p>',
                    '                <p><strong>Duration:</strong> <%= segment.time %> seconds</p>',
                    '                <p><strong>Analyse type:</strong> <%= segment.result.analyse_type %></p>',
                    '                <p><strong>Probability:</strong> <%= segment.result.probability*100 %>%</p>',
                    '            </div>',
                    '            <div class="grid-row">',
                    '                <div class="form-group">',
                    '                    <label for="transport_type" class="col-lg-2 control-label">Transporttype</label>',
                    '                    <div class="col-lg-12">',
                    '                        <select class="form-control" id="transport_type_select">',
                    '                           <% _.each(types, function(i) { %>',
                    '                               <% if(i !== segment.result.transport_type) { %>',
                    '                                   <option value="<%= i %>"><%= i %></option>',
                    '                               <% } else { %>',
                    '                                   <option value="<%= i %>" selected><%= i %></option>',
                    '                               <% }}.bind(this))   ; %>',
                    '                        </select>',
                    '                    </div>',
                    '                </div>',
                    '            </div>',
                    '        </div>',
                    '        <div class="modal-footer">',
                    '            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>',
                    '            <button type="button" class="btn btn-primary" id="saveSegment">Save</button>',
                    '        </div>',
                    '    </div>',
                    '</div>'
                ].join('');
            }
        },
        constants = {
            resultsUrl: '/api/results'
        };

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

                // add click listener to polyline
                google.maps.event.addListener(segmentPath, 'click', function () {
                    this.clickHandler(segment, segmentPath);
                }.bind(this));

                segmentPath.setMap(this.map);
            }
        },

        /**
         * Handles the click on a polyline
         * @param segment
         * @param polyline
         */
        clickHandler: function (segment, polyline) {
            var $modal = $('#segmentModal'),
                template = _.template(templates.segment());

            $modal.html('');
            $modal.html(template({segment: segment, types: transport_types}));
            $modal.modal();

            this.bindModalEvents($modal, segment, polyline);
        },

        /**
         * Binds events used in select modal
         * @param $modal
         * @param segment
         * @param polyline
         */
        bindModalEvents: function ($modal, segment, polyline) {
            var $save = $modal.find('#saveSegment'),
                $select = $modal.find('#transport_type_select'),
                resultId = $modal.find('.modal-dialog').data('resultId'),
                type;

            $select.on('change', function (event) {
                type = $(event.target).val();
            }.bind(this));

            $save.on('click', function () {
                if (!!type) {
                    this.updateTransportTypeOfSegment(segment, polyline, type);
                }
                $modal.modal('hide');
            }.bind(this));
        },

        /**
         * Makes the ajax request and sets the type to the segment
         * @param segment
         * @param polyline
         * @param type
         */
        updateTransportTypeOfSegment: function (segment, polyline, type) {
            // make ajax request
            $.ajax({
                type: 'PATCH',
                url: constants.resultsUrl + '/' + segment.result.id,
                data: {transport_type: type}
                //processData: false,
                //contentType: false
            })
            .done(function (data) {
                var color = this.getColorByType(type);
                segment.result.transport_type = type;
                polyline.setOptions({
                    strokeColor: color
                });

                polyline.icons.forEach(function (el) {
                    el.icon.strokeColor = color;
                }.bind(this));
            }.bind(this))
            .fail(function (jqXHR) {
                console.error('error during changing result!');
                console.error(jqXHR.responseJSON.error.exception[0].message)
            });
        },

        /**
         * Gets color for a transport mode type
         * @param type
         */
        getColorByType: function (type) {
            switch (type) {
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
