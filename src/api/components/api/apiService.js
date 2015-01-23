angular.module('api')
    .factory('Api', function(Theatres, Theatre, Plays, Play, Scenes, Scene, Tags, PlayTags, Shows, Show) {
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
    });