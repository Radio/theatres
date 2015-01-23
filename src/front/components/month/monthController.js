angular.module('frontApp')
    .controller('MonthController', function ($scope, DateHelper, Api) {

        $scope.theatres = [];
        $scope.shows = [];
        $scope.filter = {
            month: DateHelper.getCurrentMonth(),
            year: DateHelper.getCurrentYear(),
            theatre: null,
            scene: null
        };
        $scope.days = DateHelper.getMonthDays($scope.filter.month, $scope.filter.year);

        $scope.$watch('filter.theatre', function() {
            loadShows();
        });
        $scope.$watch('filter.scene', function() {
            loadShows();
        });
        Api.theatres.get({}).then(function(theatres) {
            $scope.theatres = theatres;
        });
        Api.scenes.get({}).then(function(scenes) {
            $scope.scenes = scenes;
        });

        // Title functions

        $scope.getTitle = function() {
            var month = DateHelper.getMonthTitle($scope.filter.month);
            var year = $scope.filter.year;

            return month + (year == DateHelper.getCurrentYear() ? '' : ' ' + year);
        };
        $scope.getSubtitle = function() {
            return $scope.filter.theatre ? $scope.filter.theatre.title : '';
        };

        // Theatres functions

        $scope.setTheatreFilter = function(theatre, $event) {
            $scope.filter.theatre = theatre;
            $event.preventDefault();
        };
        $scope.getTheatreByKey = function(key) {
            for (var i = 0; i < $scope.theatres.length; i++) {
                if ($scope.theatres[i].key == key) {
                    return $scope.theatres[i];
                }
            }
            return null;
        };

        // Scenes functions

        $scope.setSceneFilter = function(scene, $event) {
            $scope.filter.scene = scene;
            $event.preventDefault();
        };
        $scope.getSceneByKey = function(key) {
            for (var i = 0; i < $scope.scenes.length; i++) {
                if ($scope.scenes[i].key == key) {
                    return $scope.scenes[i];
                }
            }
            return null;
        };

        // Date functions

        $scope.getShowsOnDay = function(date) {
            var shows = [];
            for (var i = 0; i < $scope.shows.length; i++) {
                if (DateHelper.datesAreEqual($scope.shows[i].date, date)) {
                    shows.push($scope.shows[i]);
                }
            }

            return shows;
        };
        $scope.isToday = function(date) {
            return DateHelper.datesAreEqual(date, DateHelper.getCurrentDate());
        };

        // Private

        function loadShows()
        {
            $scope.loading = true;

            var query = {
                populate: 'play',
                month: $scope.filter.month,
                year: $scope.filter.year
            };
            if ($scope.filter.theatre) {
                query.theatre = $scope.filter.theatre.key;
            }
            if ($scope.filter.scene) {
                query.scene = $scope.filter.scene.key;
            }

            Api.shows.disableCache();
            Api.shows.get(query).then(function(response) {
                $scope.shows = response;
                $scope.loading = false;
            });
        }

    });