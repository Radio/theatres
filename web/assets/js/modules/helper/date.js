angular.module('helper')
    .factory('DateHelper', function() {

        var monthTitles = {
            'nominative': ['', 'Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            'genitive': ['', 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря']
        };
        var currentDate = new Date();

        return {
            getCurrentMonth: function() {
                return currentDate.getMonth() + 1;
            },
            getCurrentYear: function() {
                return currentDate.getFullYear();
            },
            getMonthTitle: function(monthNumber, _case) {
                return monthTitles[_case || 'nominative'][monthNumber];
            }
        };
    });