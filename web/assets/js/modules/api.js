angular.module('api', ['ngResource'])
    .constant('API_URL', '/api')
    .factory('Api', ['Theatres', 'Plays', 'Scenes', function(Theatres, Plays, Scenes) {
        return {
            theatres: Theatres,
            scenes: Scenes,
            plays: Plays
        };
    }]);