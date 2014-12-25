angular.module('frontApp', [
    'ngRoute',
    'frontApp.controllers'
])
.config(['$routeProvider', '$locationProvider', '$interpolateProvider', function($routeProvider, $locationProvider, $interpolateProvider) {
        //$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        $locationProvider.html5Mode(true);
        $routeProvider
            .when('/month', {
                templateUrl: '/assets/templates/front/month.html',
                controller: 'MonthController'
            })
            .when('/play/:playKey', {
                templateUrl: '/assets/templates/front/play.html',
                controller: 'PlayController'
            })
            .otherwise({
                redirectTo: '/month'
            });
    }]);

angular.module('frontApp.controllers', ['helper', 'api']);