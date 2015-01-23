angular.module('api')
    .factory('Factory', function($resource, $q, API_URL, localStorageService) {

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

            function getCacheKey(query) {
                return cacheKeyPrefix + JSON.stringify(query || {});
            }

            return {
                get: function(query) {
                    var deferred = $q.defer();

                    var cacheKey = getCacheKey(query);
                    var cached = useCache && localStorageService.get(cacheKey);
                    if (cached) {
                        deferred.resolve(cached);
                    } else {
                        resource.query(query, function(collection) {
                            if (collection.length) {
                                localStorageService.add(cacheKey, collection);
                                cacheKeys.push(cacheKey);
                            }
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
                            localStorageService.remove(cacheKey);
                        });
                    }

                    return deferred.promise;
                },
                enableCache: function() {
                    useCache = true;

                    return this;
                },
                disableCache: function() {
                    useCache = false;

                    return this;
                },
                resetCache: function(query) {
                    var cacheKey = getCacheKey(query);
                    if (query) {
                        localStorageService.remove(cacheKey);
                    } else {
                        cacheKey = cacheKey.replace(/\{\}$/,'.*');
                        cacheKey = cacheKey.replace(/\./,'\\.');
                        console.log(cacheKey);
                        localStorageService.clearAll(cacheKey);
                    }

                    return this;
                }
            };
        };

        var itemServiceProducer = function(resourcePath, resourceDefaults)
        {
            var resource = $resource(API_URL + '/' + resourcePath,
                resourceDefaults, {update: {method: 'PUT'}});

            return {
                get: function(id, expand) {
                    var deferred = $q.defer();
                    var query = {id: id};
                    if (expand) {
                        query.expand = expand;
                    }
                    resource.get(query, function(item) {
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
                },
                enableCache: function() {
                    // Just a plug for a moment. Cache is not used for items
                    return this;
                },
                disableCache: function() {
                    // Just a plug for a moment. Cache is not used for items
                    return this;
                },
                resetCache: function(query) {
                    localStorageService.clearAll();
                    return this;
                }
            };
        };

        return {
            collection: collectionServiceProducer,
            item: itemServiceProducer
        };
    });