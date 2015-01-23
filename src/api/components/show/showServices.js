angular.module('api')
    .factory('Shows', ['Factory', function(Factory) {
        return Factory.collection('shows');
    }])
    .factory('Show', ['Factory', function(Factory) {
        return Factory.item('shows/:id', {id: '@id'});
    }]);