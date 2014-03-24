angular.module('api')
    .factory('Scene', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/scenes/:id', {id: '@id'});

        return {
            get: function(id) {
                var deferred = $q.defer();
                resource.get({id: id}, function(scenes) {
                    deferred.resolve(scenes);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);