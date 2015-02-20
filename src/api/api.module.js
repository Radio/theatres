angular.module('api', [
        'ngResource',
        'LocalStorageModule',
        'helper'
    ])
    .constant('API_URL', '/api');