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
            var postItem = angular.copy(item);
            DateHelper.dateObjectToString(postItem, ['date']);
            return parentPost(postItem);
        };

        return showsApi;
    })
    .factory('Show',  function(Factory, DateHelper) {
        var showApi = Factory.item('shows/:id', {id: '@id'});

        var parentPut = showApi.put;
        showApi.put = function(id, data) {
            var putData = angular.copy(data);
            DateHelper.dateObjectToString(putData, ['date']);
            return parentPut(id, putData);
        };

        return  showApi;
    });