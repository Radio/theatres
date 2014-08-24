angular.module('api')
    .factory('Plays', ['Factory', function(Factory) {
        return Factory.collection('plays');
    }]);