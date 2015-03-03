angular.module('admin')
    .controller('PlaysFiltersController', function ($scope, PlaysFilters, Api) {

        $scope.filter = PlaysFilters;

        $scope.theatres = [];
        $scope.scenes = [];

        Api.theatres.get({order: 'title'}).then(function(theatres) {
            $scope.theatres = theatres;
        });
        Api.scenes.get().then(function(scenes) {
            $scope.scenes = scenes;
        });

        $scope.setTheatreFilter = function(theatre) {
            PlaysFilters.theatre = theatre;
        };
        $scope.setSceneFilter = function(scene) {
            PlaysFilters.scene = scene;
        };
    });