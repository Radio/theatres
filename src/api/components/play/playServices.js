angular.module('api')
    .factory('Plays', ['Factory', function(Factory) {
        return Factory.collection('plays');
    }])
    .factory('Play',  ['Factory', function(Factory) {
        return Factory.item('plays/:id', {id: '@id'});
    }])
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