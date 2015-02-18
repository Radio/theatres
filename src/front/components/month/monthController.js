angular.module('frontApp')
    .controller('MonthController', function ($scope, TitleHelper, DateHelper, Api, Filters) {

        TitleHelper.first = 'Все спектакли Харькова на одной странице';

        $scope.filter = Filters;

        Filters.month = DateHelper.getCurrentMonth();
        Filters.year = DateHelper.getCurrentYear();

        $scope.days = DateHelper.getMonthDays(Filters.month, Filters.year);

        loadShows();
        $scope.$on('filters-changed', function() {
            //loadShows();
        });

        $scope.$watchGroup(['filter.month','filter.year'], function() {
            TitleHelper.second = getTitle();
        });
        $scope.$watch('filter.theatre', function() {
            TitleHelper.third = getSubtitle();
        });

        $(window).scroll(function() {
            if ($(this).scrollTop() > 0) {
                $('.main-container').addClass('scrolled');
            } else {
                $('.main-container').removeClass('scrolled');
            }
        });

        // Title functions

        $scope.getTitle = getTitle;
        $scope.getSubtitle = getSubtitle;

        $scope.isToday = function(date) {
            return DateHelper.datesAreEqual(date, DateHelper.getCurrentDate());
        };

        // Private

         function getTitle() {
            var month = DateHelper.getMonthTitle(Filters.month);
            var year = Filters.year;

            return month + (year == DateHelper.getCurrentYear() ? '' : ' ' + year);
        }
        function getSubtitle() {
            return Filters.theatre ? Filters.theatre.title : '';
        }


        function loadShows()
        {
            $scope.loading = true;

            var query = buildQuery();
            return Api.shows.get(query).then(function(shows) {
                $scope.shows = shows;
                $scope.days.forEach(function(day) {
                    day.shows = getShowsOnDay(day, shows);
                });

                $scope.loading = false;

                return shows;
            });
        }

        function buildQuery() {
            var query = {
                order: 'date',
                populate: 'play,theatre,scene',
                month: Filters.month,
                year: Filters.year
            };
            return query;
        }

        function getShowsOnDay(date, shows) {
            var showsOnDay = [];
            for (var i = 0; i < shows.length; i++) {
                if (DateHelper.datesAreEqual(shows[i].date, date)) {
                    showsOnDay.push(shows[i]);
                }
            }
            return showsOnDay;
        }

    });