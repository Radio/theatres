angular.module('helper')
    .directive('calendar', function() {

        function controller($scope, DateHelper)
        {
            $scope.calendar = getCalendarDays($scope.month, $scope.year);
            $scope.dayClickHandler = function(day) {
                $scope.$emit('day-clicked', day);
            };
            $scope.isToday = function(day) {
                var currentDate = DateHelper.getCurrentDate();
                return $scope.year == currentDate.getFullYear() &&
                    $scope.month - 1 == currentDate.getMonth() &&
                    day == currentDate.getDate();
            };
        }

        function getCalendarDays(month, year) {
            var weeks = [];
            var date = moment({year: year, month: month - 1, day: 1});
            for (var week = 0; date.month() === month - 1; date.add(1, 'd')) {
                if (weeks[week] && date.weekday() === 0) {
                    week++;
                }
                if (!weeks[week]) {
                    weeks[week] = ['', '', '', '', '', '', ''];
                }
                weeks[week][date.weekday()] = date.date();
            }
            return weeks;
        }

        return {
            scope: {
                month: '=',
                year: '='
            },
            controller: controller,
            templateUrl: 'src/helper/shared/calendar/calendar.tpl.html'
        };
    });