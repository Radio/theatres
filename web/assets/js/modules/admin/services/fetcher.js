angular.module('admin.services')
    .factory('Fetcher', ['$http', '$q', function($http, $q) {
        return {
            fetch: function(theatreKey)
            {
                var url = '/admin/fetch/' + theatreKey;
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
    }]);