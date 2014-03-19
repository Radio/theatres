angular.module('api', ['ngResource'])
    .constant('API_URL', '/api')
    .factory('Api', ['Theatres', function(Theatres) {
        return {
            theatres: Theatres
        };
    }]);