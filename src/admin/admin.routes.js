angular.module('admin')
    .config(function($routeProvider) {
        $routeProvider
            .when('/admin', {
                templateUrl: 'src/admin/components/home/home.tpl.html',
                controller: 'HomeController'
            })
            .when('/admin/theatres', {
                templateUrl: 'src/admin/components/theatres/theatres.tpl.html',
                controller: 'TheatresController'
            })
            .when('/admin/scenes', {
                templateUrl: 'src/admin/components/scenes/scenes.tpl.html',
                controller: 'ScenesController'
            })
            .when('/admin/plays', {
                templateUrl: 'src/admin/components/plays/plays.tpl.html',
                controller: 'PlaysController'
            })
            .when('/admin/plays/play/:playId', {
                templateUrl: 'src/admin/components/plays/play.tpl.html',
                controller: 'PlayController'
            })
            .when('/admin/shows', {
                templateUrl: 'src/admin/components/shows/shows.tpl.html',
                controller: 'ShowsController'
            })
            .when('/admin/fetch', {
                templateUrl: 'src/admin/components/fetch/fetch.tpl.html',
                controller: 'FetchController'
            })
            .otherwise({
                redirectTo: '/admin'
            });
    });