angular.module('api')
    .factory('Api', function(Theatres, Theatre, Plays, Play, PlayTags, PlayDuplicates, Scenes, Scene, Tags, Shows, Show) {
        return {
            theatres: Theatres,
            theatre: Theatre,
            plays: Plays,
            play: Play,
            scenes: Scenes,
            scene: Scene,
            tags: Tags,
            playTags: PlayTags,
            playDuplicates: PlayDuplicates,
            shows: Shows,
            show: Show
        };
    });