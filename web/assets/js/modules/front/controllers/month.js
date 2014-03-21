angular.module('frontApp.controllers')
    .controller('MonthController', ['$scope', '$filter', 'DateHelper', 'Api', function ($scope, $filter, DateHelper, Api) {

        $scope.filter = {
            month: DateHelper.getCurrentMonth(),
            year: DateHelper.getCurrentYear(),
            theatre: null,
            scene: null
        };

        $scope.theatres = [];
        $scope.plays = [];
        $scope.days = DateHelper.getMonthDays($scope.filter.month, $scope.filter.year);

        // Title functions

        $scope.getHeaderClass = function()
        {
            return $scope.getSubtitle() ? 'with-sub-title' : '';
        };
        $scope.getTitle = function()
        {
            var month = DateHelper.getMonthTitle($scope.filter.month);
            var year = $scope.filter.year;

            return month + (year == DateHelper.getCurrentYear() ? '' : ' ' + year);
        };

        $scope.getSubtitle = function()
        {
            return $scope.filter.theatre ? $scope.filter.theatre.title : '';
        };

        // Theatres functions

        $scope.setTheatre = function(theatre, $event) {
            $scope.filter.theatre = theatre;
            $event.preventDefault();
        };
        $scope.getTheatreByKey = function(key)
        {
            for (var i = 0; i < $scope.theatres.length; i++) {
                if ($scope.theatres[i].key == key) {
                    return $scope.theatres[i];
                }
            }
            return null;
        };
        $scope.getTheatreClass = function(theatre)
        {
            if ($scope.filter.theatre) {
                if (theatre && $scope.filter.theatre.key == theatre.key) {
                    return 'current';
                }
            } else {
                if (!theatre) {
                    return 'current';
                }
            }
            return '';
        };
        $scope.getTheatreButtonTitle = function()
        {
            return $scope.filter.theatre ? $scope.filter.theatre.title : 'Все театры'
        };

        // Scenes functions

        $scope.setScene = function(scene, $event) {
            $scope.filter.scene = scene;
            $event.preventDefault();
        };
        $scope.getSceneClass = function(scene)
        {
            if ($scope.filter.scene) {
                if (scene && $scope.filter.scene.key == scene.key) {
                    return 'current';
                }
            } else {
                if (!scene) {
                    return 'current';
                }
            }
            return '';
        };
        $scope.getSceneByKey = function(key)
        {
            for (var i = 0; i < $scope.scenes.length; i++) {
                if ($scope.scenes[i].key == key) {
                    return $scope.scenes[i];
                }
            }
            return null;
        };
        $scope.getSceneButtonTitle = function()
        {
            return $scope.filter.scene ? $scope.filter.scene.title : 'Все сцены'
        };


        $scope.getPlaysOnDay = function(date)
        {
            var plays = [];
            for (var i = 0; i < $scope.plays.length; i++) {
                if (DateHelper.datesAreEqual($scope.plays[i].date, date)) {
                    plays.push($scope.plays[i]);
                }
            }

            return plays;
        };

        $scope.isToday = function(date)
        {
            return DateHelper.datesAreEqual(date, DateHelper.getCurrentDate());
        };

        $scope.getDateClass = function(date)
        {
            var classes = [];
            classes.push('day-' + date.getDay());
            if ($scope.isToday(date)) {
                classes.push('today');
            }
            return classes;
        };


        $scope.$watch('filter.theatre', function() {
            loadPlays();
        });
        $scope.$watch('filter.scene', function() {
            loadPlays();
        });

        Api.theatres.get().then(function(theatres) {
            $scope.theatres = theatres;
        });

        Api.scenes.get().then(function(scenes) {
            $scope.scenes = scenes;
        });

        function loadPlays()
        {
            $scope.loading = true;

            var query = {
                month: $scope.filter.month,
                year: $scope.filter.year
            };
            if ($scope.filter.theatre) {
                query.theatre = $scope.filter.theatre.key
            }
            if ($scope.filter.scene) {
                query.scene = $scope.filter.scene.key
            }

            Api.plays.get(query).then(function(response) {
                $scope.plays = response;

                angular.forEach($scope.plays, function(play) {
                    play.getTheatre = function() { return $scope.getTheatreByKey(play.theatre); };
                    play.getScene   = function() { return $scope.getSceneByKey(play.scene); };
                });
                $scope.loading = false;
            });
        }

    }]);