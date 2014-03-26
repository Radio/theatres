angular.module('admin.controllers')
    .controller('ScenesController', ['$scope', '$timeout', 'Api', function ($scope, $timeout, Api) {

        $scope.lastUpdatedId = null;
        $scope.newScene = {};
        $scope.scenes = [];
        $scope.filter = {};

        $scope.$watch('filter', function() {
            loadScenes();
        });

        $scope.deleteScene = function(scene)
        {
            if (confirm('Точно?')) {
                Api.scene.delete(scene.id).then(function() {
                    loadScenes();
                });
            }
        };
        $scope.addScene = function(scene)
        {
            Api.scenes.post(scene).then(function(response) {
                var newId = response.id;
                setLastUpdatedId(newId);
                $scope.newScene = {};
                loadScenes();
            });
        };
        $scope.saveScene = function(scene)
        {
            Api.scene.put(scene.id, scene).then(function(response) {
                //loadScenes();
                setLastUpdatedId(scene.id);
            });
        };

        function loadScenes()
        {
            Api.scenes.get($scope.filter).then(function(scenes) {
                $scope.scenes = scenes;
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
    }]);