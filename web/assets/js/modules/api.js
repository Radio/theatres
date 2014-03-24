angular.module('api', ['ngResource'])
    .constant('API_URL', '/api')
    .factory('Api', ['Theatres', 'Plays', 'Scenes', 'Theatre', 'Play', 'Scene', function(Theatres, Plays, Scenes, Theatre, Play, Scene) {
        return {
            theatres: Theatres,
            scenes: Scenes,
            plays: Plays,
            theatre: Theatre,
            play: Play,
            scene: Scene
        };
    }]);