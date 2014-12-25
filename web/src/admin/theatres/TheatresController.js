angular.module('admin.controllers')
    .controller('TheatresController', ['$scope', '$timeout', 'Api', function ($scope, $timeout, Api) {

        $scope.lastUpdatedId = null;
        $scope.showNewRow = false;
        $scope.newTheatre = {};
        $scope.theatres = [];
        $scope.filter = {};

        $scope.$watch('filter', function() {
            loadTheatres();
        });
        $scope.$on('theatre-updated', function(event) {
            setLastUpdatedId(event.theatreId);
        });

        $scope.deleteTheatre = function(theatre)
        {
            if (confirm('Точно?')) {
                deleteTheatre(theatre)
                    .then(function() {
                        Api.theatres.resetCache();
                        loadTheatres();
                    });
            }
        };
        $scope.addTheatre = function(theatre) {
            return createTheatre(theatre)
                .then(function() {
                    $scope.showNewRow = false;
                    $scope.newTheatre = {};
                    Api.theatres.resetCache();
                    loadTheatres();
                });
        };
        $scope.saveTheatre = saveTheatre;

        function loadTheatres()
        {
            return Api.theatres.get($scope.filter)
                .then(function(theatres) {
                    $scope.theatres = theatres;
                });
        }

        function saveTheatre(theatre)
        {
            return Api.theatre.put(theatre.id, theatre)
                .then(function(response) {
                    $scope.$broadcast('theatre-updated', {theatreId: theatre.id});
                });
        }

        function createTheatre(theatre)
        {
            return Api.theatres.post(theatre)
                .then(function(response) {
                    $scope.$broadcast('theatre-updated', {theatreId: response.id});
                });
        }

        function deleteTheatre(theatre)
        {
            return Api.theatre.delete(theatre.id)
                .then(function(response) {
                    $scope.$broadcast('theatre-deleted', {theatreId: theatre.id});
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