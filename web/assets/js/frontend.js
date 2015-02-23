(function($, viewport){

    $('body').on('click', '.scroll-top', function() {
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
                $('.scroll-top').show();
            } else {
                $('.scroll-top').hide();
            }
        }
    }

    function handleWindowResize()
    {
        if (isSmallViewport()) {
            $('.main-container').addClass('small-viewport');
        } else {
            $('.main-container').removeClass('small-viewport');
        }
    }

    function setCompactState() {
        $('.main-container').addClass('scrolled');
    }

    function setExtendedState() {
        $('.main-container').removeClass('scrolled');
    }

})($, ResponsiveBootstrapToolkit);