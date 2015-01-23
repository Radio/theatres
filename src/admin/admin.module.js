angular.module('admin', [
        'ngRoute',
        'admin-templates',
        'helper',
        'api'
    ])
    .config(function($locationProvider) {
        $locationProvider.html5Mode({
            enabled: true
        });
    });