angular.module('api')
    .factory('Play',  ['Factory', function(Factory) {
        return Factory.item('plays/:id', {id: '@id'});
    }]);