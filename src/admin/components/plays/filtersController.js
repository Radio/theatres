angular.module('admin')
    .controller('PlaysFiltersController', function ($scope, PlaysFilters, Api) {

        $scope.filter = PlaysFilters;

        $scope.setTheatreFilter = function(theatre) {
            PlaysFilters.theatre = theatre;
        };
        $scope.setSceneFilter = function(scene) {
            PlaysFilters.scene = scene;
        };
    });