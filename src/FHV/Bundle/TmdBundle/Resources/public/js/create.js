define(['map'], function (Map) {

    /**
     * TODO add some proper validation
     * TODO extract constants into variables
     */

    var constants = {
        mapId: 'map',
        gpsFileSelector: '#gpsFile',

        tracksUrl: '/api/tracks'
    };


    return {

        $formView: null,
        $mapView: null,

        $form: null,
        $submitButton: null,
        $errorForm: null,


        /**
         * Initialize form
         */
        initialize: function () {

            this.$form = $('#createForm');
            this.$submitButton = $('#createFormSubmit');
            this.$errorForm = $('#errorForm');
            this.$formView = $('#formView');
            this.$mapView = $('#mapView');

            this.bindEvents();
        },

        bindEvents: function () {
            // handle submit of form
            this.$form.submit(function (event) {
                this.formSubmitHandler(event);
            }.bind(this));
        },

        /**
         * Handling submit of form
         * @param event
         */
        formSubmitHandler: function (event) {
            var data = this.getValidatedFormData();

            event.preventDefault();
            event.stopPropagation();

            if (!!data) {
                this.$submitButton.html('Sending file and processing data...');
                this.$submitButton.addClass('disabled');
                this.submit(data, 'POST');
            } else {
                this.showInvalidDataMessage();
            }
        },

        /**
         * Shows an error message when the data in the form is not valid
         */
        showInvalidDataMessage: function () {
            this.$errorForm.show();
            this.$errorForm.fadeOut(3000).delay(5000);
        },

        /**
         * return the data of the form or null if invalid
         * @returns FormData|null
         */
        getValidatedFormData: function () {
            var file = $(constants.gpsFileSelector)[0].files[0],
                method = $('input[name="typeOptions"]:checked').val(),
                formData = new FormData();

            if (!!file && !!method) {
                formData.append('file', file);
                formData.append('method', method);
                return formData;
            }
            return null;
        },

        /**
         * Send data to server
         * @param data
         * @param method
         */
        submit: function (data, method) {
            $.ajax({
                type: method,
                url: constants.tracksUrl,
                data: data,
                processData: false,
                contentType: false
            })
            .done(function (data) {
                this.fileUploadedHandler(data); // could be done earlier
            }.bind(this))
            .fail(function (jqXHR) {
                console.error('error during upload or processing of the file!');
                console.error(jqXHR.responseJSON.error.exception[0].message)
            });
        },

        /**
         * Will be triggered when the file is uploaded
         * Hides the form and shows a map
         */
        fileUploadedHandler: function (data) {
            this.$formView.hide();
            this.$mapView.show();
            Map.initialize(constants.mapId);
            Map.drawTrack(data);
        }
    };
});
