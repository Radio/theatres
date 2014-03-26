angular.module('api')
    .factory('Plays', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/plays');

        return {
            get: function(query) {
                var deferred = $q.defer();
                resource.query(query, function(plays) {
                    deferred.resolve(plays);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            },
            post: function(play) {
                var deferred = $q.defer();
                resource.save(play, function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);