angular.module('api')
    .factory('PlayTags', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {

        var resource = $resource(API_URL + '/plays/:id/tags', {id: '@id'});

        return {
            get: function(id) {
                var deferred = $q.defer();
                resource.query({id: id}, function(tags) {
                    deferred.resolve(tags);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);