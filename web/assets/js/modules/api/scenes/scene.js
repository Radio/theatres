angular.module('api')
    .factory('Scene', ['Factory', function(Factory) {
        return Factory.item('theatres/:id', {id: '@id'});
    }]);