define(['async!//maps.googleapis.com/maps/api/js?v=3.exp'], function (googleMaps) {

    /**
     * TODO add some proper validation
     * TODO extract constants into variables
     * TODO extract map handling into a serparate component
     */

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
                this.$submitButton.html('Sending file and data...');
                this.$submitButton.addClass('disabled');
                this.submit(data);
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
            var file = $('#gpsFile')[0].files[0],
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
         */
        submit: function (data) {
            var request = $.ajax({
                type: 'POST',
                url: '/api/tracks',
                data: data,
                processData: false,
                contentType: false
            });

            request.done(function () {
                this.fileUploadedHandler();
            }.bind(this));

            request.fail(function () {
                alert("error during upload or processing of the file!");
            });
        },

        /**
         * Will handle
         */
        fileUploadedHandler: function () {
            this.$formView.hide();
            this.$mapView.show();
            this.initMap();
        },

        /**
         * Initializes the map
         */
        initMap: function () {
            var mapOptions = {
                    zoom: 10,
                    center: new google.maps.LatLng(47.185203,10.0249882)

                },
                map = new google.maps.Map(document.getElementById('map'), mapOptions);
        }
    };
});
