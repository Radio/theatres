angular.module('helper')
    .factory('TitleHelper', function($rootScope) {
        $rootScope.title = {
            first: '',
            second: '',
            third: ''
        };

        return $rootScope.title;
    });