(function($, viewport){

    var isSmallViewport;

    var $mainContainer = $('.main-container');
    var $scrollTop = $('.scroll-top');
    $scrollTop.click(function() {
        $('html, body').scrollTop(0);
    });

    if ($mainContainer.data('ng-app') == 'frontApp') {
        handleWindowResize();

        $(window)
            .bind('scroll', handelWindowScroll)
            .bind('resize', function() {
                viewport.changed(handleWindowResize);
            });
    }

    function handelWindowScroll() {
        if ($(window).scrollTop() > 100) {
            $scrollTop.show();
            setCompactState();
        } else {
            $scrollTop.hide();
            if (!isSmallViewport) {
                setExtendedState();
            }
        }
    }

    function handleWindowResize()
    {
        isSmallViewport = viewport.is('xs') || viewport.is('sm');
        if (isSmallViewport) {
            setCompactState();
        } else {
            setExtendedState();
        }
    }

    function setCompactState() {
        $mainContainer.addClass('scrolled');
    }

    function setExtendedState() {
        $mainContainer.removeClass('scrolled');
    }

})($, ResponsiveBootstrapToolkit);