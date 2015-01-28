angular.module('admin')
    .directive('cacheManager', function(localStorageService) {

            function controller($scope) {
                $scope.clear = function() {
                    localStorageService.clearAll();
                };
            }

            return {
                scope: {},
                controller: ['$scope', controller],
                templateUrl: 'src/admin/shared/cache-manager/cache-manager.tpl.html'
            };
        });