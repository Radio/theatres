angular.module('api', ['ngResource', 'LocalStorageModule'])
    .constant('API_URL', '/api')
    .factory('Api', ['Theatres', 'Theatre', 'Plays', 'Play', 'Scenes', 'Scene', 'Tags', 'PlayTags', 'Shows', 'Show',
        function(Theatres, Theatre, Plays, Play, Scenes, Scene, Tags, PlayTags, Shows, Show) {
            return {
                theatres: Theatres,
                theatre: Theatre,
                plays: Plays,
                play: Play,
                scenes: Scenes,
                scene: Scene,
                tags: Tags,
                playTags: PlayTags,
                shows: Shows,
                show: Show
            };
        }]);