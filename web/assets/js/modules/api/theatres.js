angular.module('api')
    .factory('Theatres', ['Factory', function(Factory) {
        return Factory.collection('theatres');
    }]);