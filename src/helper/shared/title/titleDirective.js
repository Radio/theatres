angular.module('helper')
    .directive('theatresTitle', function() {

        function controller($scope, DateHelper, TitleHelper, FilterHelper, RoutingHelper)
        {
            $scope.date = DateHelper;
            $scope.title = TitleHelper;
            $scope.routing = RoutingHelper;

            $scope.title.first = 'Все спектакли Харькова на одной странице';

            $scope.getCurrentMonthTitle = function () {
                return DateHelper.getMonthTitle(DateHelper.getCurrentMonth());
            };

            $scope.getNextMonthTitle = function () {
                var nextMonth = DateHelper.getNextMonth();
                var month = DateHelper.getMonthTitle(nextMonth);

                return month + (nextMonth > 1 ? '' : ' ' + DateHelper.getCurrentYear() + 1);
            };

            $scope.getTheatreTitle = function () {
                var theatre = FilterHelper.theatre;
                return theatre ? theatre.title : '';
            };

            $scope.isCurrentMonth = function() {
                return DateHelper.getCurrentMonth() == FilterHelper.month;
            };
        }

        return {
            scope: {},
            controller: controller,
            templateUrl: 'src/helper/shared/title/title.tpl.html'
        };
    });