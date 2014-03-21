angular.module('api')
    .factory('Scenes', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/scenes');

        return {
            get: function(query) {
                var deferred = $q.defer();
                resource.query(query, function(scenes) {
                    deferred.resolve(scenes);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);