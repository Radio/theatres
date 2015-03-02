angular.module('admin')
    .controller('PlayDuplicateController', function ($scope, $routeParams, $location, Api) {

        $scope.original = {};
        $scope.play = {};
        $scope.plays = [];

        Api.play.get($routeParams.playId).then(function(play) {
            $scope.play = play;

            Api.plays.get({
                theatre: play.theatre_id,
                order: 'title'
            }).then(function(plays) {
                $scope.plays = plays;
            });
        });


        $scope.replacePlay = function(originalPlay)
        {
            if (confirm('Точно?')) {
                Api.playDuplicates.post(originalPlay.id, $scope.play.id)
                    .then(function() {
                        $location.url('/admin/plays/play/' + originalPlay.id);
                    });
            }
        };

    });