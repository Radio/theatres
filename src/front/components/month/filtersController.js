angular.module('frontApp')
    .controller('MonthFiltersController', function ($scope, DateHelper, Api, Filters) {

        $scope.theatres = [];
        $scope.scenes = [];
        $scope.filter = Filters;

        $scope.$on('day-clicked', function(event, day) {
            var $dayNode = $('.date-' + day);
            if ($dayNode.length) {
                $('html, body').scrollTop($dayNode.offset().top - 60);
            }
        });

        Api.theatres.get({}).then(function(theatres) {
            theatres.forEach(function() {

            });
            $scope.theatres = theatres;
        });
        Api.scenes.get({}).then(function(scenes) {
            $scope.scenes = scenes;
        });

        $scope.setTheatreFilter = function(theatre, $event) {
            Filters.theatre = theatre;
            $scope.$emit('filters-changed', 'theatre');
        };
        $scope.setSceneFilter = function(scene, $event) {
            $event.preventDefault();
            $scope.filter.scene = scene;
            $scope.$emit('filters-changed', 'scene');
        };
    })
    .filter('showsFilter', function() {
        return function(input, filters) {
            var filtered = [];
            if (input) {
                for (var i = 0; i < input.length; i++) {
                    if (filters.theatres && filters.theatres[input[i].theatre_id] === false) {
                        continue;
                    }
                    filtered.push(input[i]);
                }
            }
            return filtered;
        };
    });