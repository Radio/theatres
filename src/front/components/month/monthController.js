angular.module('frontApp')
    .controller('MonthController', function ($scope, $routeParams, $timeout, TitleHelper, DateHelper, Api, Filters) {

        var shownDay;

        initTitle();
        initFilters();
        loadShows().then(function() {
            $("html, body").scrollTop(0);
            finalizeLoadingState();
        });

        $scope.$watchGroup(['filter.month', 'filter.year'], function() {
            TitleHelper.second = getTitle();
        });
        $scope.$watchGroup(['filter.theatre', 'filterTheatre'], function() {
            TitleHelper.third = getSubtitle();
        });
        $scope.$watchCollection(['title'], function() {
            fixPosterMargin();
        });

        $scope.$watchCollection('filter.playTypes', fixScrolledToDay);
        $scope.$watchCollection('filter.scenes', fixScrolledToDay);
        $scope.$on('day-clicked', function(event, day) {
            shownDay = day;
            scrollToDay(day);
        });

        $scope.isToday = function(date) {
            return DateHelper.datesAreEqual(date, DateHelper.getCurrentDate());
        };

        // Private

        function initTitle() {
            TitleHelper.first = 'Все спектакли Харькова на одной странице';
            $scope.getTitle = getTitle;
            $scope.getSubtitle = getSubtitle;
        }

        function getTitle() {
            var month = DateHelper.getMonthTitle(Filters.month);
            var year = Filters.year;

            return month + (year == DateHelper.getCurrentYear() ? '' : ' ' + year);
        }

        function getSubtitle() {
            var theatre = Filters.theatre || $scope.filterTheatre;
            return theatre ? theatre.title : '';
        }

        function loadShows()
        {
            $scope.loading = true;
            $scope.days = DateHelper.getMonthDays(Filters.month, Filters.year);

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
            if (Filters.theatre) {
                query.theatre = Filters.theatre.id;
            }
            if (Filters.theatreKey) {
                query.theatreKey = Filters.theatreKey;
            }
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

        function initFilters() {
            Filters.month = DateHelper.getCurrentMonth();
            Filters.year = DateHelper.getCurrentYear();
            if ($routeParams.theatreKey) {
                Filters.theatreKey = $routeParams.theatreKey;
                Api.theatre.get('@' + $routeParams.theatreKey).then(function(theatre) {
                    $scope.filterTheatre = theatre;
                });
            } else {
                Filters.theatreKey = null;
                $scope.filterTheatre = null;
            }

            $scope.filter = Filters;
        }

        function scrollToDay(day) {
            var $dayNode = $('.date-' + day);
            if ($dayNode.length) {
                $("html, body").animate({
                    scrollTop: $dayNode.offset().top - $('.main-header').height()
                }, '300', 'swing');
            }
        }

        function isSmallViewport() {
            return ResponsiveBootstrapToolkit.is('xs') || ResponsiveBootstrapToolkit.is('sm');
        }

        function fixScrolledToDay()
        {
            if (shownDay) {
                if (!isSmallViewport()) {
                    setTimeout(function() {
                        scrollToDay(shownDay);
                    }, 300);
                }
            }
        }

        function fixPosterMargin()
        {
            if (isSmallViewport()) {
                var tries = 5;
                var interval = setInterval(function() {
                    var headerHeight = $('.main-header').height();
                    $('.scroll-top').css({'top': headerHeight});
                    $('.filters-col').css({'margin-top': headerHeight});
                    if (!--tries) {
                        clearInterval(interval);
                    }
                }, 500);
            }
        }

        function finalizeLoadingState()
        {
            $timeout(function() {
                // needed for phantomjs to detect the loading finish.
                $scope.$parent.status = 'ready';
            }, 500);
        }

    });