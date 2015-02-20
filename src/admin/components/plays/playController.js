angular.module('admin')
    .controller('PlayController', function ($scope, $routeParams, $timeout, $location, Api) {

        $('html, body').scrollTop(0);

        $scope.play = {};
        $scope.theatres = [];
        $scope.scenes = [];
        $scope.playTags = [];

        Api.play.get($routeParams.playId).then(function(play) {
            $scope.play = play;
            Api.playTags.get(play.id).then(function(tags) {
                $scope.playTags = tags;
            });
        });
        Api.theatres.get().then(function(theatres) {
            $scope.theatres = theatres;
        });
        Api.scenes.get().then(function(scenes) {
            $scope.scenes = scenes;
        });

        $scope.savePlay = function(play)
        {
            Api.play.put(play.id, play).then(function(response) {
                //loadPlays();
                setLastUpdatedId(play.id);
            });
        };

        $scope.deletePlay = function(play)
        {
            if (confirm('Точно?')) {
                Api.play.delete(play.id).then(function() {
                    $location.url('/admin/plays');
                });
            }
        };

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