angular.module('api')
    .factory('Tags', ['Factory', function(Factory) {
        return Factory.collection('tags');
    }]);