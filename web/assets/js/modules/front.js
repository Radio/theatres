angular.module('frontApp', [
    'ngRoute',
    'frontApp.controllers'
])
.config(['$routeProvider', '$locationProvider', '$interpolateProvider', function($routeProvider, $locationProvider, $interpolateProvider) {
        //$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
        $locationProvider.html5Mode(true);
        $routeProvider
            .when('/month', {
                templateUrl: '/templates/front_app.month.html',
                controller: 'MonthController'
            })
            .when('/play/:playKey', {
                templateUrl: '/templates/front_app.play.html',
                controller: 'PlayController'
            })
            .otherwise({
                redirectTo: '/month'
            });
    }]);

angular.module('frontApp.controllers', ['helper', 'api']);