angular.module('api')
    .factory('Theatres', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/theatres');

        return {
            get: function() {}
        };
    }]);