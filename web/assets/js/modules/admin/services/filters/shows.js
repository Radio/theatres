angular.module('admin.services')
    .factory('ShowsFilters', [function() {
        return {
            order: 'date',

            theatre: null,
            scene: null,
            date: null
        };
    }]);