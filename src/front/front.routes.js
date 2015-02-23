angular.module('frontApp')
    .config(function($routeProvider) {

        var monthRoutingConfig = {
            templateUrl: 'src/front/components/month/month.tpl.html',
            controller: 'MonthController'
        };

        $routeProvider
            .when('/', monthRoutingConfig)
            .when('/theatre/:theatreKey', monthRoutingConfig)
            .when('/month/:month', monthRoutingConfig)
            .when('/month/:month/theatre/:theatreKey', monthRoutingConfig)
            .when('/play/:playKey', {
                templateUrl: 'src/front/components/play/play.tpl.html',
                controller: 'PlayController'
            })
            .otherwise({
                redirectTo: '/'
            });
    });