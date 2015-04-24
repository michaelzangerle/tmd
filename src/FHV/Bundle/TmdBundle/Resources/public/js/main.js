requirejs.config({
    baseUrl: '/bundles/fhvtmd/js/',
    paths: {
        'jquery': '../vendor/jquery/dist/jquery.min',
        'bootstrap': '../vendor/bootstrap/dist/js/bootstrap.min',
        'material': '../vendor/bootstrap-material-design/dist/js/material.min',
        'async': '../vendor/requirejs-plugins/src/async'
    },

    shim: {
        'jquery': {
            exports: '$'
        },
        'bootstrap': {
            deps: ['jquery']
        },
        'material': {
            deps: ['bootstrap'],
            exports: '$.material'
        }
    }
});

require(['jquery', 'create', 'material'], function ($, create, material) {

    $.material.init();
    console.log('finished init!');

    create.initialize();
});
