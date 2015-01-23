angular.module('frontApp')
    .config(function($routeProvider) {
        $routeProvider
            .when('/month', {
                templateUrl: 'src/front/components/month/month.tpl.html',
                controller: 'MonthController'
            })
            .when('/play/:playKey', {
                templateUrl: 'src/front/components/play/play.tpl.html',
                controller: 'PlayController'
            })
            .otherwise({
                redirectTo: '/month'
            });
    });