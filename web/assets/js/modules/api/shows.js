angular.module('api')
    .factory('Shows', ['$resource', '$q', 'API_URL', function($resource, $q, API_URL) {
        var resource = $resource(API_URL + '/shows');

        return {
            get: function(query) {
                var deferred = $q.defer();
                resource.query(query, function(shows) {
                    for (var i = 0; i < shows.length; i++) {
                        shows[i].date = new Date(shows[i].date);
                    }
                    deferred.resolve(shows);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            },
            post: function(show) {
                var deferred = $q.defer();
                resource.save(show, function(response) {
                    deferred.resolve(response);
                }, function(response) {
                    deferred.reject(response);
                });

                return deferred.promise;
            }
        };
    }]);