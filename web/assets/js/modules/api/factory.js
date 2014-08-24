angular.module('api')
    .factory('Factory', ['$resource', '$q', 'API_URL', 'localStorageService',
        function($resource, $q, API_URL, Storage) {

            function dateStringToObject(item)
            {
                if (typeof item.date == 'string') {
                    item.date = new Date(item.date);
                }
            }
            function dateObjectToString(item)
            {
                if (typeof item.date == 'object') {
                    item.date = moment(item.date).format('YYYY-MM-DD HH:mm:ss');
                }
            }

            var collectionServiceProducer = function(resourcePath, resourceDefaults) {
                var resource = $resource(API_URL + '/' + resourcePath, resourceDefaults || {});
                var useCache = true;
                var cacheKeys = [];
                var cacheKeyPrefix = resourcePath.replace('/\//g', '.') + '.';

                return {
                    get: function(query) {
                        var deferred = $q.defer();

                        var cacheKey = cacheKeyPrefix + JSON.stringify(query || {});
                        var cached = useCache && Storage.get(cacheKey);
                        if (cached) {
                            angular.forEach(cached, dateStringToObject);
                            deferred.resolve(cached);
                        } else {
                            resource.query(query, function(collection) {
                                Storage.add(cacheKey, collection);
                                cacheKeys.push(cacheKey);
                                angular.forEach(collection, dateStringToObject);
                                deferred.resolve(collection);
                            }, function(response) {
                                deferred.reject(response);
                            });
                        }

                        return deferred.promise;
                    },
                    post: function(item) {
                        var deferred = $q.defer();

                        dateObjectToString(item);

                        resource.save(item, function(response) {
                            deferred.resolve(response);
                        }, function(response) {
                            deferred.reject(response);
                        });

                        if (useCache) {
                            angular.forEach(cacheKeys, function(cacheKey) {
                                Storage.remove(cacheKey);
                            });
                        }

                        return deferred.promise;
                    },
                    enableCache: function() {
                        useCache = true;
                    },
                    disableCache: function() {
                        useCache = false;
                    }
                };
            };

            var itemServiceProducer = function(resourcePath, resourceDefaults)
            {
                var resource = $resource(API_URL + '/' + resourcePath,
                    resourceDefaults, {update: {method: 'PUT'}});

                return {
                    get: function(id) {
                        var deferred = $q.defer();
                        resource.get({id: id}, function(item) {
                            dateStringToObject(item);
                            deferred.resolve(item);
                        }, function(response) {
                            deferred.reject(response);
                        });

                        return deferred.promise;
                    },
                    put: function(id, data) {
                        var deferred = $q.defer();

                        data.id = id;
                        dateObjectToString(data);

                        resource.update(data, function(response) {
                            deferred.resolve(response);
                        }, function(response) {
                            deferred.reject(response);
                        });

                        return deferred.promise;
                    },
                    'delete': function(id) {
                        var deferred = $q.defer();
                        resource.delete({id: id}, function(response) {
                            deferred.resolve(response);
                        }, function(response) {
                            deferred.reject(response);
                        });

                        return deferred.promise;
                    }
                };
            };

            return {
                collection: collectionServiceProducer,
                item: itemServiceProducer
            };
        }]);