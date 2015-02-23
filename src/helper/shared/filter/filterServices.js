angular.module('helper')
    .factory('FilterHelper', function() {
        return {
            month: null,
            year: null,
            theatre: null,
            theatreKey: null,

            playTypes: {},
            scenes: {}
        };
    });