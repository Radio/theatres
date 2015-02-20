angular.module('frontApp')
    .controller('PlayController', function ($scope, $routeParams, $filter, Api) {

        $scope.play = {};
        $scope.theatre = {};
        $scope.scene = {};

        Api.play.get($routeParams.playKey).then(function(play) {

            Api.theatre.get('@' + play.theatre).then(function(_theatre) {
                $scope.theatre = _theatre;
            });
            Api.scene.get('@' + play.scene).then(function(_scene) {
                $scope.scene = _scene;
            });

            $scope.play = play;
        });

        $scope.getTitle = function() {
            return $scope.play.title;
        };
        $scope.getSubtitle = function() {
            return $scope.theatre.title +
                ', ' + $filter('date')($scope.play.date, 'd MMMM H:mm');
        };

    });