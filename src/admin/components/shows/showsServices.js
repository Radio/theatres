angular.module('admin')
    .factory('ShowsFilters', function() {
        return {
            order: 'date',

            theatre: null,
            scene: null,
            date: null,
            from: null,
            to: null
        };
    });