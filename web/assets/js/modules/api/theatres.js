angular.module('api')
    .factory('Theatres', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/theatres');

        return {
            get: function(query) {
                var deferred = $q.defer();
                resource.query(query, function(theatres) {
                    deferred.resolve(theatres);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            },
            post: function(theatre) {
                var deferred = $q.defer();
                resource.save(theatre, function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);