angular.module('helper')
    .factory('RoutingHelper', function(FilterHelper, DateHelper) {
        return {
            getHomeUrl: function() {
                return '#!/';
            },
            getUrl: function(year, month, theatreKey) {
                year = (year !== undefined) ? year : FilterHelper.year;
                month = (month !== undefined) ? month : FilterHelper.month;
                theatreKey = (theatreKey !== undefined ? theatreKey :
                    (FilterHelper.theatre ? FilterHelper.theatre.key : null));

                var url = this.getHomeUrl();
                if (month != DateHelper.getCurrentMonth() || year != DateHelper.getCurrentYear()) {
                    url += '/month/' + year + '-' + month;
                }
                if (theatreKey) {
                    url += '/theatre/' + theatreKey;
                }

                return url.replace('//', '/');
            }
        };
    });