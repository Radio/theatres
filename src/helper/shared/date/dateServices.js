angular.module('helper')
    .factory('DateHelper', function() {

        var monthTitles = {
            'nominative': ['', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            'genitive': ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря']
        };
        var currentDate = new Date();

        return {
            getCurrentDate: function ()
            {
                return currentDate;
            },
            getCurrentMonth: function() {
                return currentDate.getMonth() + 1;
            },
            getCurrentYear: function() {
                return currentDate.getFullYear();
            },
            getMonthTitle: function(monthNumber, _case) {
                return monthTitles[_case || 'nominative'][monthNumber];
            },
            getMonthDays: function(monthNumber, year) {
                var days = [];
                var weeks = [];
                var daysInMonth = this.getNumberOfDaysInMonth(monthNumber, year);
                for (var i = 1; i <= daysInMonth; i++) {
                    days.push(new Date(year, monthNumber - 1, i));
                }
                return days;
            },
            getNumberOfDaysInMonth: function(monthNumber, year) {
                return new Date(year, monthNumber, 0).getDate();
            },
            datesAreEqual: function(date1, date2) {
                return date1.getFullYear() == date2.getFullYear() &&
                    date1.getMonth() == date2.getMonth() &&
                    date1.getDate() == date2.getDate();
            }
        };
    });