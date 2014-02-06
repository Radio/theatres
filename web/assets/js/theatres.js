(function($) {

    var baseTitle = document.title;

    var $mainContainer = $('#main');

    var $month = $('.month');
    if ($month.length) {

        $('.show-week').click(function(event) {
            event.preventDefault();
            var $week = $(this).parents('.week');

            location.hash = '#week-' + $week.data('index');
        });

        $('.show-month').click(function(event) {
            event.preventDefault();

            location.hash = '';
        });

        function showWeek($week)
        {
            $mainContainer.removeClass('view-month').addClass('view-week');
            $week.addClass('active');

            document.title = baseTitle + ' / ' + $week.data('title');
            scrollToTop();
        }

        function showMonth()
        {
            $mainContainer.removeClass('view-week').addClass('view-month');

            var $week = $('.week.active');
            $week.removeClass('active');

            document.title = baseTitle;
            scrollToTop();
        }

        function scrollToTop()
        {
            var top = $(".page-header").offset().top - 10;
            var $html = $('html');
            if ($html.scrollTop() > top) {
                $html.animate({
                    scrollTop: top
                }, 300);
            }
        }

        window.onpopstate = function(event)
        {
            var weekId = location.hash.replace("#week-", '');
            if (weekId) {
                showWeek($('.week-' + weekId));
            } else {
                showMonth();
            }
        };
    }

})(jQuery);
