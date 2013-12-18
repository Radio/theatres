(function($) {

    var $mainContainer = $('#main');

    $('.show-week').click(function(event) {
        event.preventDefault();
        var $week = $(this).parents('.week');
        showWeek($week);
    });

    $('.show-month').click(function(event) {
        event.preventDefault();
        showMonth();
    });

    function showWeek($week)
    {
        $mainContainer.removeClass('view-month').addClass('view-week');
        $week.addClass('active');
    }

    function showMonth()
    {
        $mainContainer.removeClass('view-week').addClass('view-month');

        var $week = $('.week.active');
        $week.removeClass('active');
    }

})(jQuery);
