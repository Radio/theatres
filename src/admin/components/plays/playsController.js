angular.module('admin')
    .controller('PlaysController', function ($scope, $timeout, Api, PlaysFilters) {

        $('html, body').scrollTop(0);

        $scope.lastUpdatedId = null;
        $scope.newPlay = {};
        $scope.plays = [];

        $scope.filter = PlaysFilters;

        $scope.$watch('filter.theatre', function() {
            loadPlays();
        });
        $scope.$watch('filter.scene', function() {
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
                setLastUpdatedId(play.id);
                //loadPlays();
            });
        };
        $scope.editPlay = function(play)
        {
            // todo: put play to storage before rendering form.
        };

        function loadPlays()
        {
            var query = {};
            if ($scope.filter.theatre) {
                query.theatre = $scope.filter.theatre.id;
            }
            if ($scope.filter.scene) {
                query.scene = $scope.filter.scene.id;
            }

            Api.plays.get(query).then(function(plays) {
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
    });