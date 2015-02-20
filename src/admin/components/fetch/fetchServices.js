angular.module('admin')
    .factory('Fetcher', function($http, $q) {
        return {
            fetch: function(theatreKey) {
                var url = '/fetch/' + theatreKey;
                var deferred = $q.defer();
                $http.get(url)
                    .success(function(data) {
                        deferred.resolve(data);
                    })
                    .error(function(response) {
                        deferred.reject(response);
                    });
                return deferred.promise;
            }
        };
    });