angular.module('frontApp')
    .directive('showBriefMeta', function () {
        return {
            scope: {
                show: '='
            },
            controller: function() {},
            templateUrl: 'src/front/components/month/show-brief-meta.tpl.html'
        };
    });