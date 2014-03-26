angular.module('api')
    .factory('Scene', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/scenes/:id', {id: '@id'}, {update: {method: 'PUT'}});

        return {
            get: function(id) {
                var deferred = $q.defer();
                resource.get({id: id}, function(scenes) {
                    deferred.resolve(scenes);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            },
            put: function(id, data) {
                var deferred = $q.defer();
                data.id = id;
                resource.update(data, function(scene) {
                    deferred.resolve(scene);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            },
            'delete': function(id) {
                var deferred = $q.defer();
                resource.delete({id: id}, function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);