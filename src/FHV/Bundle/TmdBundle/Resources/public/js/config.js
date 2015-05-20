requirejs.config({
    baseUrl: '/bundles/fhvtmd/js/',
    paths: {
        'jquery': '../vendor/jquery/dist/jquery.min',
        'bootstrap': '../vendor/bootstrap/dist/js/bootstrap.min',
        'material': '../vendor/bootstrap-material-design/dist/js/material.min',
        'async': '../vendor/requirejs-plugins/src/async',
        'underscore': '../vendor/underscore/underscore-min',
        'chart': '../vendor/Chart.js/Chart.min'
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
        },
        underscore: {
            exports: '_'
        }
    }
});
