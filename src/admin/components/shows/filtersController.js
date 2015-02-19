angular.module('admin')
    .controller('ShowsFiltersController', function ($scope, ShowsFilters, Api) {

        $scope.filter = ShowsFilters;

        groupPlaysByTheatre();

        $scope.setTheatreFilter = function(theatre) {
            ShowsFilters.theatre = theatre;
        };
        $scope.setSceneFilter = function(scene) {
            ShowsFilters.scene = scene;
        };

        function groupPlaysByTheatre()
        {
            var grouped = {};
            if ($scope.plays) {
                for (var i = 0; i < $scope.plays.length; i++) {
                    var play = $scope.plays[i];
                    if (!grouped[play.theatre_id]) {
                        grouped[play.theatre_id] = [];
                    }
                    grouped[play.theatre_id].push(play);
                }
            }

            return grouped;
        }
    });