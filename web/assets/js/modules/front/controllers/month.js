angular.module('frontApp.controllers')
    .controller('MonthController', ['$scope', 'DateHelper', 'Api', function ($scope, DateHelper, Api) {

        $scope.filter = {
            month: DateHelper.getCurrentMonth(),
            year: DateHelper.getCurrentYear(),
            theatre: null
        };

        $scope.getTitle = function() {
            var month = DateHelper.getMonthTitle($scope.filter.month);
            var year = $scope.filter.year;

            return month + (year == DateHelper.getCurrentYear() ? '' : ' ' + year);
        };

        $scope.getSubtitle = function() {
            return $scope.filter.theatre ? $scope.filter.theatre.title : '';
        };

        console.log(Api.theatres);


    }]);