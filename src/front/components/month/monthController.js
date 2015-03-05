angular.module('frontApp')
    .controller('MonthController', function ($scope, $rootScope, $routeParams, $timeout, $location, Api, DateHelper, FilterHelper, RoutingHelper) {

        var shownDay;

        $scope.routing = RoutingHelper;

        initFilters();
        loadShows().then(function() {
            $("html, body").scrollTop(0);
            finalizeLoadingState();
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

        $scope.getShowClass = function(show) {
            return {
                premiere: show.play_is_premiere,
                musical: show.play_is_musical,
                dance: show.play_is_dance,
                for_children: show.play_is_for_children
            };
        };

        // Private

        function loadShows()
        {
            $scope.loading = true;
            $scope.days = DateHelper.getMonthDays(FilterHelper.month, FilterHelper.year);

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

        $scope.loadDetails = function(show)
        {
            if (show.play_id && !show._is_extended) {
                Api.play.get(show.play_id).then(function(play) {
                    ['description', 'image', 'duration', 'author', 'director'].forEach(function(prop) {
                        show['play_' + prop]  = play[prop];
                    });
                    show._is_extended = true;
                });
            }
        };

        function buildQuery() {
            var query = {
                order: 'date',
                populate: 'play,theatre,scene',
                month: FilterHelper.month,
                year: FilterHelper.year
            };
            if (FilterHelper.theatre) {
                query.theatre = FilterHelper.theatre.id;
            } else if (FilterHelper.theatreKey) {
                query.theatreKey = FilterHelper.theatreKey;
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
            if ($routeParams.month) {
                var monthYear = $routeParams.month.split('-');
                FilterHelper.year = monthYear[0];
                FilterHelper.month = monthYear[1];
                if (!isValidYearAndMonth(FilterHelper.year, FilterHelper.month)) {
                    $location.path('/');
                    return;
                }
            } else {
                FilterHelper.month = DateHelper.getCurrentMonth();
                FilterHelper.year = DateHelper.getCurrentYear();
            }


            if ($routeParams.theatreKey) {
                FilterHelper.theatreKey = $routeParams.theatreKey;
                Api.theatre.get('@' + $routeParams.theatreKey).then(function(theatre) {
                    FilterHelper.theatre = theatre;
                }, function() {
                    $location.path('/');
                });
            } else {
                FilterHelper.theatreKey = null;
                FilterHelper.theatre = null;
            }

            $scope.filter = FilterHelper;

            function isValidYearAndMonth(year, month) {
                return year > 2014 && year < 3000 && month > 0 && month <= 12;
            }
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
                $rootScope.status = 'ready';
            }, 500);
        }

    }).filter('newlines', function($sce) {
        return function(text) {
            return $sce.trustAsHtml((text || '').replace(/\n/g, '<br/>'));
        }
    });