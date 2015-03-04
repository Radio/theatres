angular.module('admin')
    .controller('PlayTagsController', function ($scope, $routeParams, $timeout, Api) {

        var playId = $routeParams.playId;
        var plainTags = [];

        $scope.updateTags = updateTags;
        $scope.tags = tagsGetterSetter;

        Api.play.get(playId).then(function(play) {
            $scope.play = play;
        });

        loadPlayTags();

        // private

        function tagsGetterSetter(tags)
        {
            if (angular.isDefined(tags)) {
                plainTags = tags.length ? tags.split('\n') : [];
                return tags;
            } else {
                if (plainTags.length == 1 && plainTags[0] === "") {
                    plainTags = [];
                }
                return plainTags.join('\n');
            }
        }

        function updateTags()
        {
            Api.playTags.post(playId, plainTags).then(function() {
                loadPlayTags();
                setLastUpdatedId(playId);
            });
        }

        function loadPlayTags()
        {
            return Api.playTags.get(playId).then(function(tags) {
                plainTags = [];
                for (var i = 0; i < tags.length; i++) {
                    plainTags.push(tags[i].title);
                }

                return tags;
            });
        }

        var timeoutPromise = null;
        function setLastUpdatedId(id)
        {
            $scope.lastUpdatedId = id;
            if (timeoutPromise) {
                $timeout.cancel(timeoutPromise);
            }
            timeoutPromise = $timeout(function() {
                $scope.lastUpdatedId = null;
                timeoutPromise = null;
            }, 5000);
        }

    });