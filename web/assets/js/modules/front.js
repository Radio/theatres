angular.module('frontApp', [
    'ngRoute',
    'frontApp.controllers'
])
.config(['$interpolateProvider', function($interpolateProvider) {
    //$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
}])
.config(['$routeProvider', function($routeProvider) {
        $routeProvider
            .when('/month', {
                templateUrl: 'templates/front_app.month.html',
                controller: 'MonthController'
            })
            .when('/play/:playKey', {
                templateUrl: 'templates/front_app.play.html',
                controller: 'PlayController'
            })
            .otherwise({
                redirectTo: '/month'
            });
    }]);

angular.module('frontApp.controllers', ['helper', 'api']);