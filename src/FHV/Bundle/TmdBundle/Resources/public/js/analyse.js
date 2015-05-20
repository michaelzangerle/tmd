require(['jquery', 'chart', 'material'], function ($, Chart) {

    // TODO comment

    var constants = {
            totalUrl: '/api/analyse',
            correctAnalysedUrl: '/api/analyse?type=detail',
            falseAnalysedModeUrl: '/api/analyse?type=detail&mode=bus'
        },
        analyse = {
            initialize: function () {
                $.material.init();
                Chart.defaults.global.responsive = true;
                Chart.defaults.global.multiTooltipTemplate = "<%= datasetLabel %> - <%= value %>"

                // todo show loader

                this.totalChartCtx = $('#totalChart').get(0).getContext("2d");
                this.correctAnalyzedChartCtx = $('#correctAnalysed').get(0).getContext("2d");
                this.falseAnalyzedChartCtx = $('#falseAnalysed').get(0).getContext("2d");

                var dfdLoadData = this.loadData();
                $.when(dfdLoadData).done(function (data) {
                    this.showCharts(data)
                }.bind(this));
            },

            loadData: function () {
                var method = 'GET',
                    total = this.makeAjaxRequest(constants.totalUrl, method),
                    corrAnalysed = this.makeAjaxRequest(constants.correctAnalysedUrl, method),
                    falseAnalysed = this.makeAjaxRequest(constants.falseAnalysedModeUrl, method), // TODO for all modes?
                    dfdLoad = $.Deferred();

                $.when(total, corrAnalysed, falseAnalysed).done(function (totalData, corrAnalysedData, falseAnalysedData) {
                    var data = {
                        total: totalData[0],
                        correctAnalysed: corrAnalysedData[0],
                        falseAnalysed: falseAnalysedData[0]
                    };
                    dfdLoad.resolve(data);
                }.bind(this));

                return dfdLoad;
            },

            makeAjaxRequest: function (url, method, data) {
                return $.ajax({
                    type: method,
                    url: url,
                    data: data
                })
                    .fail(function (jqXHR) {
                        console.error('error during upload or processing of the file!');
                        console.error(jqXHR.responseJSON.error.exception[0].message)
                    }.bind(this));
            },

            showCharts: function (data) {
                var totalData = this.getDataForChart(data.total),
                    correctData = this.getDataForChart(data.correctAnalysed),
                    falseData = this.getDataForChart(data.falseAnalysed);

                new Chart(this.totalChartCtx).Bar(totalData);
                new Chart(this.correctAnalyzedChartCtx).Bar(correctData);
                new Chart(this.falseAnalyzedChartCtx).Bar(falseData);
            },

            getDataForChart: function (data) {

                var result = {labels: [], datasets: []}, key, analyseType, values, ds,
                    dataset =
                    {
                        label: '',
                        fillColor: 'rgba(220,220,220,0.5)',
                        strokeColor: 'rgba(220,220,220,0.8)',
                        highlightFill: 'rgba(220,220,220,0.75)',
                        highlightStroke: 'rgba(220,220,220,1)',
                        data: []
                    };

                // TODO change color

                for (key in data) {
                    analyseType = data[key];
                    values = [];
                    for (type in analyseType) {
                        values.push(analyseType[type])
                        result.labels.push(type);
                    }

                    ds = $.extend({}, dataset);
                    ds.data = values;
                    ds.label = key;
                    result.datasets.push(ds);

                }

                return result;
            }
        };

    analyse.initialize();
});
