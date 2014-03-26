angular.module('admin.controllers')
    .controller('PlaysController', ['$scope', '$timeout', 'Api', function ($scope, $timeout, Api) {

        $scope.lastUpdatedId = null;
        $scope.newPlay = {};
        $scope.plays = [];
        $scope.filter = {};

        $scope.$watch('filter', function() {
            loadPlays();
        });

        $scope.deletePlay = function(play)
        {
            if (confirm('Точно?')) {
                Api.play.delete(play.id).then(function() {
                    loadPlays();
                });
            }
        };
        $scope.addPlay = function(play)
        {
            Api.plays.post(play).then(function(response) {
                var newId = response.id;
                setLastUpdatedId(newId);
                $scope.newPlay = {};
                loadPlays();
            });
        };
        $scope.savePlay = function(play)
        {
            Api.play.put(play.id, play).then(function(response) {
                //loadPlays();
                setLastUpdatedId(play.id);
            });
        };

        function loadPlays()
        {
            Api.plays.get($scope.filter).then(function(plays) {
                $scope.plays = plays;
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