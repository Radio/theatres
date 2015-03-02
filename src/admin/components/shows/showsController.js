angular.module('admin')
    .controller('ShowsController', function ($scope, $timeout, Api, ShowsFilters, DateHelper) {

        $('html, body').scrollTop(0);

        initFilters();

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
        Api.plays.get({populate: 'theatre', order: 'title'}).then(function(plays) {
            $scope.plays = plays;
        });

        $scope.$watchGroup(['filter.from', 'filter.to', 'filter.theatre', 'filter.scene'], function() {
            loadShows();
        });

        $scope.setShowDefaults = function(show) {
            var play = $scope.getPlayById(show.play_id);
            if (play) {
                show.theatre_id = play.theatre_id;
                show.scene_id = play.scene_id;
            }
        };

        $scope.getTheatreById = function(id) {
            for (var i = 0; i < $scope.theatres.length; i++) {
                if ($scope.theatres[i].id == id) {
                    return $scope.theatres[i];
                }
            }
            return null;
        };

        $scope.getPlayById = function(id) {
            for (var i = 0; i < $scope.plays.length; i++) {
                if ($scope.plays[i].id == id) {
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
            if (show.play_id) {
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
                setLastUpdatedId(show.id);
            });
        };

        function loadShows()
        {
            if ($scope.filter.from && $scope.filter.to) {

                var query = {
                    order: $scope.filter.order,
                    date_from: $scope.filter.from,
                    date_to: $scope.filter.to
                };
                if ($scope.filter.theatre) {
                    query.theatre = $scope.filter.theatre.id;
                }
                if ($scope.filter.scene) {
                    query.scene = $scope.filter.scene.id;
                }
                $scope.loading = true;

                Api.shows.get(query).then(function(shows) {
                    $scope.shows = shows;
                    $scope.loading = false;
                });
            }
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

        function initFilters() {
            var currentYear = DateHelper.getCurrentYear();
            var currentMonth = DateHelper.getCurrentMonth();
            currentMonth = currentMonth < 10 ? '0' + currentMonth : currentMonth;

            ShowsFilters.from = '01.' + currentMonth + '.' + currentYear;
            ShowsFilters.to = '10.' + currentMonth + '.' + currentYear;
        }
    });