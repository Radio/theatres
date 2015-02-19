angular.module('frontApp')
    .controller('MonthFiltersController', function ($scope, DateHelper, Api, Filters) {

        $scope.filter = Filters;

        $scope.playTypes = [
            {id: 'is_for_children', title: 'Для детей'},
            {id: 'is_for_adults', title: 'Для взрослых'},
            {id: 'is_musical', title: 'Музыкальные'}
        ];
        initPlayTypesFilter($scope.playTypes);

        $scope.scenes = [];
        Api.scenes.get({}).then(function(scenes) {
            $scope.scenes = scenes;
            initScenesFilter($scope.scenes);
        });

        // private

        function initPlayTypesFilter(playTypes)
        {
            angular.forEach(playTypes, function(playType) {
                Filters.playTypes[playType.id] = true;
            });
        }

        function initScenesFilter(scenes)
        {
            angular.forEach(scenes, function(scene) {
                Filters.scenes[scene.id] = true;
            });
        }
    })
    .filter('showsFilter', function() {
        return function(input, filters) {
            var filtered = [];
            if (input) {
                for (var i = 0; i < input.length; i++) {
                    if (filters.theatre && filters.theatre.id != input[i].theatre_id) {
                        continue;
                    }
                    if (filters.scenes && filters.scenes[input[i].scene_id] === false) {
                        continue;
                    }
                    if (input[i].play_is_musical && filters.playTypes.is_musical === false) {
                        continue;
                    }
                    if (input[i].play_is_for_children) {
                        if (filters.playTypes.is_for_children === false) {
                            continue;
                        }
                    } else {
                        if (filters.playTypes.is_for_adults === false) {
                            continue;
                        }
                    }

                    filtered.push(input[i]);
                }
            }
            return filtered;
        };
    });