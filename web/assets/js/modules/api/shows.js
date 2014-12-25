angular.module('api')
    .factory('Shows', ['Factory', function(Factory) {
        return Factory.collection('shows');
    }]);