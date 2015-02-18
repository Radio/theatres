angular.module('frontApp')
    .factory('Filters', function() {
        return {
            month: null,
            year: null,
            theatre: null,

            playTypes: {},
            scenes: {}
        };
    });