angular.module('api')
    .factory('Theatres', ['Factory', function(Factory) {
        return Factory.collection('theatres');
    }])
    .factory('Theatre',  ['Factory', function(Factory) {
        return Factory.item('theatres/:id', {id: '@id'});
    }]);