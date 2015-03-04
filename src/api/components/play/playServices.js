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
            },
            post: function(id, tags) {
                var deferred = $q.defer();
                resource.save({
                    id: id,
                    tags: tags
                }, function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }])
    .factory('PlayDuplicates', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {

        var resource = $resource(API_URL + '/plays/:id/duplicates', {id: '@id'});

        return {
            post: function(id, duplicate) {
                var deferred = $q.defer();

                resource.save({
                    id: id,
                    duplicate: duplicate
                }, function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);