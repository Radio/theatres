angular.module('api')
    .factory('Shows', function(Factory, DateHelper) {
        var showsApi = Factory.collection('shows');

        var parentGet = showsApi.get;
        showsApi.get = function(query) {
            return parentGet(query).then(function(collection) {
                collection.forEach(function(item) {
                    DateHelper.dateStringToObject(item, ['date']);
                });
                return collection;
            });
        };

        var parentPost = showsApi.post;
        showsApi.post = function(item) {
            DateHelper.dateObjectToString(item, ['date']);
            var promise = parentPost();
            DateHelper.dateStringToObject(item, ['date']);
            return promise;
        };

        return showsApi;
    })
    .factory('Show',  function(Factory) {
        return Factory.item('shows/:id', {id: '@id'});
    });