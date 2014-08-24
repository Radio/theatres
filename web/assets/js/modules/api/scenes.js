angular.module('api')
    .factory('Scenes', ['Factory', function(Factory) {
        return Factory.collection('scenes');
    }]);