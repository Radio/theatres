angular.module('admin.controllers')
    .controller('ShowsFiltersController', ['$scope', 'ShowsFilters', 'Api', function ($scope, ShowsFilters, Api) {

        $scope.filter = ShowsFilters;

        $scope.theatres = [];
        $scope.scenes = [];

        Api.theatres.get().then(function(theatres) {
            $scope.theatres = theatres;
        });
        Api.scenes.get().then(function(scenes) {
            $scope.scenes = scenes;
        });

        $scope.setTheatreFilter = function(theatre) {
            ShowsFilters.theatre = theatre;
        };
        $scope.setSceneFilter = function(scene) {
            ShowsFilters.scene = scene;
        };
    }]);