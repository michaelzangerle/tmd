require(['jquery', 'chart', 'material'], function ($, Chart) {

    var constants = {
            totalUrl: '/api/analyse',
            correctAnalysedUrl: '/api/analyse?type=detail',
            falseAnalysedModeUrl: '/api/analyse?type=detail&mode=',
            modes: ['bus', 'drive', 'walk', 'bike', 'train'],
            blue: '96,125,139',
            green: '0,150,136'
        },
        analyse = {

            /**
             * Initializes everything the page needs
             */
            initialize: function () {
                $.material.init();
                Chart.defaults.global.responsive = true;
                Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %> - <%= value %>"

                // todo show loader

                this.totalChartCtx = $('#totalChart').get(0).getContext("2d");
                this.correctAnalyzedChartCtx = $('#correctAnalysed').get(0).getContext("2d");
                this.falseAnalyzedBusChartCtx = $('#falseAnalysedBus').get(0).getContext("2d");
                this.falseAnalyzedWalkChartCtx = $('#falseAnalysedWalk').get(0).getContext("2d");
                this.falseAnalyzedDriveChartCtx = $('#falseAnalysedDrive').get(0).getContext("2d");
                this.falseAnalyzedBikeChartCtx = $('#falseAnalysedBike').get(0).getContext("2d");
                this.falseAnalyzedTrainChartCtx = $('#falseAnalysedTrain').get(0).getContext("2d");

                var dfdLoadData = this.loadData();
                $.when(dfdLoadData).done(function (data) {
                    this.showCharts(data)
                }.bind(this));
            },

            /**
             * Loads the data for all the charts
             * @returns Object
             */
            loadData: function () {
                var method = 'GET',
                    dfds = [],
                    dfdLoad = $.Deferred();

                dfds.push(this.makeAjaxRequest(constants.totalUrl, method));
                dfds.push(this.makeAjaxRequest(constants.correctAnalysedUrl, method));
                constants.modes.forEach(function (el) {
                    dfds.push(this.makeAjaxRequest(constants.falseAnalysedModeUrl + el, method));
                }.bind(this));

                $.when.apply($, dfds).done(
                    function (totalData,
                              corrAnalysedData,
                              falseAnalysedBusData,
                              falseAnalysedDriveData,
                              falseAnalysedWalkData,
                              falseAnalysedBikeData,
                              falseAnalysedTrainData) {
                        var data = {
                            total: totalData[0],
                            correctAnalysed: corrAnalysedData[0],
                            falseAnalysedBus: falseAnalysedBusData[0],
                            falseAnalysedDrive: falseAnalysedDriveData[0],
                            falseAnalysedWalk: falseAnalysedWalkData[0],
                            falseAnalysedBike: falseAnalysedBikeData[0],
                            falseAnalysedTrain: falseAnalysedTrainData[0]
                        };
                        dfdLoad.resolve(data);
                    }.bind(this));

                return dfdLoad;
            },

            /**
             * Makes an ajax request
             * @param url
             * @param method
             * @param data
             * @returns {*}
             */
            makeAjaxRequest: function (url, method) {
                return $.ajax({
                    type: method,
                    url: url
                }).fail(function (jqXHR) {
                    console.error('error while fetching results!');
                    console.error(jqXHR.responseJSON.error.exception[0].message)
                }.bind(this));
            },

            /**
             * Initializes the chats with the data
             * @param data
             */
            showCharts: function (data) {
                var totalData = this.getDataForChart(data.total),
                    correctData = this.getDataForChart(data.correctAnalysed),
                    falseBusData = this.getDataForChart(data.falseAnalysedBus),
                    falseWalkData = this.getDataForChart(data.falseAnalysedWalk),
                    falseDriveData = this.getDataForChart(data.falseAnalysedDrive),
                    falseBikeData = this.getDataForChart(data.falseAnalysedBike),
                    falseTrainData = this.getDataForChart(data.falseAnalysedTrain);

                new Chart(this.totalChartCtx).Bar(totalData);
                new Chart(this.correctAnalyzedChartCtx).Bar(correctData);
                new Chart(this.falseAnalyzedBusChartCtx).Bar(falseBusData);
                new Chart(this.falseAnalyzedWalkChartCtx).Bar(falseWalkData);
                new Chart(this.falseAnalyzedDriveChartCtx).Bar(falseDriveData);
                new Chart(this.falseAnalyzedBikeChartCtx).Bar(falseBikeData);
                new Chart(this.falseAnalyzedTrainChartCtx).Bar(falseTrainData);
            },

            /**
             * Prepares the data for the charts
             * @param data
             * @returns {{labels: Array, datasets: Array}}
             */
            getDataForChart: function (data) {

                var key, analyseType, values, ds,
                    i = 0,
                    result = {labels: [], datasets: []},
                    dataset = this.getDataSetDummy(constants.blue),
                    dataset2 = this.getDataSetDummy(constants.green);

                for (key in data) {
                    analyseType = data[key];
                    values = [];
                    for (type in analyseType) {
                        values.push(analyseType[type])
                        result.labels.push(type);
                    }

                    if (i % 2) {
                        ds = $.extend({}, dataset);
                    } else {
                        ds = $.extend({}, dataset2);
                    }
                    ds.data = values;
                    ds.label = key;
                    result.datasets.push(ds);
                }

                return result;
            },

            /**
             * Creates a dummy object for charts
             * @param color
             * @returns {{label: string, fillColor: string, strokeColor: string, highlightFill: string, highlightStroke: string, data: Array}}
             */
            getDataSetDummy: function (color) {
                var tmp = null;
                if (color === constants.blue) {
                    tmp = 'rgba(' + constants.blue;
                } else {
                    tmp = 'rgba(' + constants.green;
                }

                return {
                    label: '',
                    fillColor: tmp + ',0.5)',
                    strokeColor: tmp + ',0.8)',
                    highlightFill: tmp + ',0.75)',
                    highlightStroke: 'rgba(96,125,139,1)',
                    data: []
                };
            }
        };

    analyse.initialize();
});
