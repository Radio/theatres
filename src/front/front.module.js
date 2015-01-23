angular.module('frontApp', [
        'ngRoute',
        'front-templates',
        'helper',
        'api'
    ])
    .config(function($locationProvider) {
        $locationProvider.html5Mode({
            enabled: true,
            requireBase: false
        });
    });