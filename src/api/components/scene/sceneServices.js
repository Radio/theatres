angular.module('api')
    .factory('Scenes', function(Factory) {
        return Factory.collection('scenes');
    })
    .factory('Scene', function(Factory) {
        return Factory.item('theatres/:id', {id: '@id'});
    });