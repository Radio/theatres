angular.module('api')
    .factory('Play', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/plays/:id', {id: '@id'});

        return {
            get: function(id) {
                var deferred = $q.defer();
                resource.get({id: id}, function(play) {
                    if (typeof play.date != 'undefined') {
                        play.date = new Date(play.date);
                    }
                    deferred.resolve(play);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);