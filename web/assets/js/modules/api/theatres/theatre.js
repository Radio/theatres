angular.module('api')
    .factory('Theatre',  ['Factory', function(Factory) {
        return Factory.item('theatres/:id', {id: '@id'});
    }]);