angular.module('admin')
    .controller('ShowsController', function ($scope, $timeout, Api, ShowsFilters) {

        $('html, body').scrollTop(0);

        $scope.lastUpdatedId = null;
        $scope.newShow = {};
        $scope.shows = [];
        $scope.theatres = [];
        $scope.scenes = [];
        $scope.plays = [];
        $scope.filter = ShowsFilters;

        Api.theatres.get().then(function(theatres) {
            $scope.theatres = theatres;
        });
        Api.scenes.get().then(function(scenes) {
            $scope.scenes = scenes;
        });
        Api.plays.get().then(function(plays) {
            $scope.plays = plays;
        });

        $scope.$watch('filter.date', function() {
            loadShows();
        });
        $scope.$watch('filter.theatre', function() {
            loadShows();
        });
        $scope.$watch('filter.scene', function() {
            loadShows();
        });
        $scope.$watch('filter.date', function() {
            loadShows();
        });

        $scope.setShowDefaults = function(show) {
            var play = $scope.getPlayByKey(show.play);
            if (play) {
                show.theatre = play.theatre;
                show.scene = play.scene;
            }
        };

        $scope.getTheatreByKey = function(key) {
            for (var i = 0; i < $scope.theatres.length; i++) {
                if ($scope.theatres[i].key == key) {
                    return $scope.theatres[i];
                }
            }
            return null;
        };

        $scope.getPlayByKey = function(key) {
            for (var i = 0; i < $scope.plays.length; i++) {
                if ($scope.plays[i].key == key) {
                    return $scope.plays[i];
                }
            }
            return null;
        };

        $scope.deleteShow = function(show)
        {
            if (confirm('Точно?')) {
                Api.show.delete(show.id).then(function() {
                    loadShows();
                });
            }
        };
        $scope.addShow = function(show)
        {
            if (show.play) {
                Api.shows.post(show).then(function(response) {
                    var newId = response.id;
                    setLastUpdatedId(newId);
                    $scope.newShow = {};
                    loadShows();
                });
            }
        };
        $scope.saveShow = function(show)
        {
            Api.show.put(show.id, show).then(function(response) {
                //loadShows();
                setLastUpdatedId(show.id);
            });
        };

        function loadShows()
        {
            var query = {
                order: $scope.filter.order
            };
            if ($scope.filter.theatre) {
                query.theatre = $scope.filter.theatre.key;
            }
            if ($scope.filter.scene) {
                query.scene = $scope.filter.scene.key;
            }
            $scope.loading = true;

            Api.shows.get(query).then(function(shows) {
                $scope.shows = shows;
                $scope.loading = false;
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