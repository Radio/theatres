angular.module('admin')
    .controller('FetchController', function ($scope, $timeout, Api, Fetcher) {

        $scope.theatres = [];
        $scope.filter = {
            'fetchable': 'yes'
        };

        $scope.$watch('filter', function() {
            loadTheatres();
        });

        $scope.fetchAll = function()
        {
            angular.forEach($scope.theatres, function(theatre) {
                $scope.fetchTheatre(theatre);
            });
        };

        $scope.fetchTheatre = function(theatre)
        {
            setFetching(theatre, 'active', null, null);

            Fetcher.fetch(theatre.key)
                .then(function(response) {
                    if (response.status == 'success') {
                        setFetching(theatre, 'inactive', 'success', response.message);
                    } else {
                        setFetching(theatre, 'inactive', 'failure', response.message);
                    }
                }, function() {
                    setFetching(theatre, 'inactive', 'failure', 'Ошибка сервера.');
                });
        };

        function setFetching(theatre, status, result, message)
        {
            theatre.fetching = {
                status: status,
                result: result,
                message: message
            };
        }

        function loadTheatres()
        {
            Api.theatres.get($scope.filter).then(function(theatres) {
                angular.forEach(theatres, function(theatre) {
                    setFetching(theatre, null, null, null);
                });
                $scope.theatres = theatres;
            });
        }
    });