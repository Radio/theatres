angular.module('admin.controllers')
    .controller('TheatresController', ['$scope', '$timeout', 'Api', function ($scope, $timeout, Api) {

        $scope.lastUpdatedId = null;
        $scope.newTheatre = {};
        $scope.theatres = [];
        $scope.filter = {};

        $scope.$watch('filter', function() {
            loadTheatres();
        });

        $scope.deleteTheatre = function(theatre)
        {
            if (confirm('Точно?')) {
                Api.theatre.delete(theatre.id).then(function() {
                    loadTheatres();
                });
            }
        };
        $scope.addTheatre = function(theatre)
        {
            Api.theatres.post(theatre).then(function(response) {
                var newId = response.id;
                setLastUpdatedId(newId);
                $scope.newTheatre = {};
                loadTheatres();
            });
        };
        $scope.saveTheatre = function(theatre)
        {
            Api.theatre.put(theatre.id, theatre).then(function(response) {
                //loadTheatres();
                setLastUpdatedId(theatre.id);
            });
        };

        function loadTheatres()
        {
            Api.theatres.get($scope.filter).then(function(theatres) {
                $scope.theatres = theatres;
            });
        }

        var timeoutPromise = null;
        function setLastUpdatedId(id)
        {
            $scope.lastUpdatedId = id;
            if (timeoutPromise) {
                $timeout.cancel(timeoutPromise);
            }
            timeoutPromise = $timeout(function() {
                $scope.lastUpdatedId = null;
                timeoutPromise = null;
            }, 5000);
        }
    }]);