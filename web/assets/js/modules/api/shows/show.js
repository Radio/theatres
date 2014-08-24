angular.module('api')
    .factory('Show', ['Factory', function(Factory) {
        return Factory.item('shows/:id', {id: '@id'});
    }]);