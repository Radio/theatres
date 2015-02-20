(function($, viewport){

    var $mainContainer = $('.main-container');
    var $scrollTop = $('.scroll-top');
    $scrollTop.click(function() {
        $('html, body').scrollTop(0);
    });

    if ($('html').data('ng-app') == 'frontApp') {
        handleWindowResize();
        setCompactState();

        $(window)
            .bind('scroll', handelWindowScroll)
            .bind('resize', function() {
                viewport.changed(handleWindowResize);
            });
    }

    function isSmallViewport() {
        return viewport.is('xs') || viewport.is('sm');
    }

    function handelWindowScroll() {
        if (isSmallViewport()) {
            if ($(window).scrollTop() > 100) {
                $scrollTop.show();
            } else {
                $scrollTop.hide();
            }
        }
    }

    function handleWindowResize()
    {
        if (isSmallViewport()) {
            $mainContainer.addClass('small-viewport');
        } else {
            $mainContainer.removeClass('small-viewport');
        }
    }

    function setCompactState() {
        $mainContainer.addClass('scrolled');
    }

    function setExtendedState() {
        $mainContainer.removeClass('scrolled');
    }

})($, ResponsiveBootstrapToolkit);