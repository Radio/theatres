angular.module('api')
    .factory('Plays', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/plays');

        return {
            get: function(query) {
                var deferred = $q.defer();
                resource.query(query, function(plays) {
                    for (var i = 0; i < plays.length; i++) {
                        plays[i].date = new Date(plays[i].date);
                    }
                    deferred.resolve(plays);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);