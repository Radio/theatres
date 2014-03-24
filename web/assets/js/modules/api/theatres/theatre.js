angular.module('api')
    .factory('Theatre', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/theatres/:id', {id: '@id'});

        return {
            get: function(id) {
                var deferred = $q.defer();
                resource.get({id: id}, function(theatre) {
                    deferred.resolve(theatre);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);