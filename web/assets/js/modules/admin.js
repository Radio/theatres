angular.module('admin', [
        'ngRoute',
        'admin.services',
        'admin.controllers'
    ])
    .config(['$routeProvider', '$locationProvider', '$interpolateProvider', function($routeProvider, $locationProvider, $interpolateProvider) {
        //$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        $locationProvider.html5Mode(true);
        $routeProvider
            .when('/admin', {
                templateUrl: '/templates/admin.home.html',
                controller: 'HomeController'
            })
            .when('/admin/theatres', {
                templateUrl: '/templates/admin.theatres.html',
                controller: 'TheatresController'
            })
            .when('/admin/scenes', {
                templateUrl: '/templates/admin.scenes.html',
                controller: 'ScenesController'
            })
            .when('/admin/plays', {
                templateUrl: '/templates/admin.plays.html',
                controller: 'PlaysController'
            })
            .when('/admin/shows', {
                templateUrl: '/templates/admin.shows.html',
                controller: 'ShowsController'
            })
            .when('/admin/fetch', {
                templateUrl: '/templates/admin.fetch.html',
                controller: 'FetchController'
            })
            .otherwise({
                redirectTo: '/admin'
            });
    }]);

angular.module('admin.controllers', ['helper', 'api']);
angular.module('admin.services', []);