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
                templateUrl: '/assets/templates/admin/home.html',
                controller: 'HomeController'
            })
            .when('/admin/theatres', {
                templateUrl: '/assets/templates/admin/theatres.html',
                controller: 'TheatresController'
            })
            .when('/admin/scenes', {
                templateUrl: '/assets/templates/admin/scenes.html',
                controller: 'ScenesController'
            })
            .when('/admin/plays', {
                templateUrl: '/assets/templates/admin/plays.html',
                controller: 'PlaysController'
            })
            .when('/admin/plays/play/:playId', {
                templateUrl: '/assets/templates/admin/plays/play.html',
                controller: 'PlayController'
            })
            .when('/admin/shows', {
                templateUrl: '/assets/templates/admin/shows.html',
                controller: 'ShowsController'
            })
            .when('/admin/fetch', {
                templateUrl: '/assets/templates/admin/fetch.html',
                controller: 'FetchController'
            })
            .otherwise({
                redirectTo: '/admin'
            });
    }]);

angular.module('admin.controllers', ['helper', 'api']);
angular.module('admin.services', []);