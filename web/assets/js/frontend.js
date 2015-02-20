(function($, viewport){

    var $mainContainer = $('.main-container');

    if ($mainContainer.data('ng-app') == 'frontApp') {
        handleWindowResize();

        $(window).bind('resize', function() {
            viewport.changed(function(){
                handleWindowResize();
            });
        });

        function handelWindowScroll() {
            if ($(window).scrollTop() > 100) {
                setCompactState()
            } else {
                setExtendedState()
            }
        }
    }

    function handleWindowResize()
    {
        if (viewport.is('xs') || viewport.is('sm')) {
            setCompactState();
            $(window).unbind('scroll', handelWindowScroll);
        } else {
            setExtendedState();
            $(window).bind('scroll', handelWindowScroll);
        }
    }

    function setCompactState() {
        $mainContainer.addClass('scrolled');
    }

    function setExtendedState() {
        $mainContainer.removeClass('scrolled');
    }

})($, ResponsiveBootstrapToolkit);