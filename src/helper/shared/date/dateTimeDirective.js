angular.module('helper')
    .directive('dateTime', function ($window) {
        return {
            require: '^ngModel',
            restrict: 'A',
            link: function (scope, elm, attrs, ctrl) {
                var moment = $window.moment;
                var dateFormat = attrs.dateTime;
                attrs.$observe('dateTime', function (newValue) {
                    if (dateFormat == newValue || !ctrl.$modelValue) {
                        return;
                    }
                    dateFormat = newValue;
                    ctrl.$modelValue = new Date(ctrl.$setViewValue);
                });

                ctrl.$formatters.unshift(function (modelValue) {
                    if (!dateFormat || !modelValue) {
                        return "";
                    }
                    return moment(modelValue).format(dateFormat);
                });

                ctrl.$parsers.unshift(function (viewValue) {
                    var date = moment(viewValue, dateFormat);
                    return (date && date.isValid() && date.year() > 1950 ) ? date.toDate() : "";
                });
            }
        };
    });