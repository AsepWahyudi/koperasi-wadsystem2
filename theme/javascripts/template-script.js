/**
 * Created by myii-developer.
 * v-1.2
 * MUNICH TEMPLATE
 */
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

$(function() {
    "use strict";

    $(document).on('click', function(e) {

        //collapse header elements when click outside of them
        if (!$('#notice-headerbox').find(e.target).length) {
            $("#notice-headerbox .notice.open").removeClass('open').children('.dropdown-box').slideUp(200);
        }
        if (!$('#user-headerbox').find(e.target).length) {
            $("#user-headerbox.open").removeClass('open').children('.user-options').slideUp(400);
        }
        if (!$('#search-headerbox').find(e.target).length) {
            if ($("#search").is(":visible")) {
                $("#search").slideToggle();
            }
        }
    });

    // TOGGLE CLASS on click
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    function toggleClassOnClick(object) {

        var target = object.attr('data-target');
        var toggleClass = object.attr('data-toggle-class');

        object.on('click.toggleClass.fireEvent', function(e) {

            e.preventDefault();
            $(target).toggleClass(toggleClass);
        });
    };
    $('[data-toggle-class][data-target]').each(function() {
        toggleClassOnClick($(this));
    });


    //NAVIGATION LEFT-SIDEBAR
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    function openItemLeftSidbar(item) {
        item.children('ul.child-nav').slideDown(500, function() {
            $(this).css('display', '');
        });
        item.addClass('open-item').removeClass('close-item');
    };

    function closeItemLeftSidbar(item) {
        item.children('ul.child-nav').slideUp(300, function() {
            $(this).css('display', '');
            item.addClass('close-item').removeClass('open-item');
        });
    };

    var main_nav = $('#main-nav');

    //OPEN NAV ITEMS
    //-------------------------------------------------------------------
    main_nav.on('click', 'li.close-item > a', function() {
        var parent = $(this).parent('li');
        openItemLeftSidbar(parent);

        parent.siblings('li.open-item').each(function() {
            closeItemLeftSidbar($(this));
        });
    });
    //CLOSE NAV ITEMS
    //-------------------------------------------------------------------
    main_nav.on('click', 'li.open-item > a', function() {
        var parent = $(this).parent('li');
        closeItemLeftSidbar(parent);
    });



    // NOTIFICACTION HEADERBOX
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    function openItem(item) {
        item.children('.dropdown-box').slideDown(400);
    }

    function closeItem(item) {
        item.children('.dropdown-box').slideUp(200);
    }

    function closeSiblings(item) {
        item.siblings('.notice.open').each(function() {
            closeItem($(this));
            $(this).removeClass('open');
        });
    }
    $('#notice-headerbox .notice i').on('click', function(event) {

        var item = $(this).parent();
        item.toggleClass('open');

        if (item.hasClass('open')) {
            closeSiblings(item);
            openItem(item)
        } else {
            closeItem(item)
        }
    });

    //USER HEADERBOX DROPDOWN
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $('#user-headerbox').on('click', function(event) {

        var options = $(this).children('.user-options');
        $(this).toggleClass('open');

        if ($(this).hasClass('open')) {
            options.slideDown(400);
        } else {
            options.slideUp(400);
        }
    });

    // SEARCH HEADERBOX
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $('#search-icon').on('click', function() {
        $("#search").slideToggle();
    });

    // PANEL ACTIONS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $('.panel')
        // expand panels
        .on('click', '.toggle-panel.panel-expand', function() {

            var panel = $(this).closest('.panel');

            panel.children('.panel-content, .panel-footer').slideUp(400);
            $(this).addClass('panel-collapse').removeClass('panel-expand');
        })
        // collapse panels
        .on('click', '.toggle-panel.panel-collapse', function() {

            var panel = $(this).closest('.panel');

            panel.children('.panel-content, .panel-footer').slideDown(400);
            $(this).addClass('panel-expand').removeClass('panel-collapse');

        })
        // remove panels
        .on('click', '.remove-panel', function() {

            var panel = $(this).closest('.panel');
            var parent = panel.parent();

            if (parent.is('[class*="col-"]') && parent.children().length == 1) {
                parent.fadeOut(500, function() {
                    parent.remove();
                });
            } else {
                panel.fadeOut(300, function() {
                    panel.remove();
                });
            }
        });


    //SCROLL TO TOP
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    var scroll = $('.scroll-to-top');

    // Scroll to top
    scroll.on('click', function() {
        $("html, body").animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    // Show-Hide scrollToTop Button
    $(window).on('scroll', function() {
        if ($(this).scrollTop() > 100) {
            scroll.fadeIn();
        } else {
            scroll.fadeOut();
        }
    });

    // RIGHT SIDEBAR TEMPLATE SETTINGS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    var html = $('html');
    var header_fixed = $('#header-fixed');
    var content_header_fixed = $('#content-header-fixed');
    var left_sidebar_top = $('#left-sidebar-top');
    var left_sidebar_fixed = $('#left-sidebar-fixed');
    var left_sidebar_collapse = $('#left-sidebar-collapsed');
    var left_sidebar_over = $('#left-sidebar-over');
    var left_sidebar_left_lines = $('#left-sidebar-left-lines');

    //FIXED HEADER
    header_fixed.on('change', function(event) {
        if (header_fixed.is(':checked')) {

            fixedStyle();
        } else {
            scrollStyle();
            left_sidebar_fixed.removeAttr('checked')
            content_header_fixed.removeAttr('checked')
        }
    });

    //FIXED CONTENT HEADER
    content_header_fixed.on('change', function(event) {

        if (content_header_fixed.is(':checked')) {
            fixedStyle();
            html.removeClass('content-header-scroll');

        } else {
            html.addClass('content-header-scroll');
        }
    });

    //LEFT SIDEBAR TOP 
    left_sidebar_top.on('change', function(event) {

        if (left_sidebar_top.is(':checked')) {
            html.addClass('left-sidebar-top');
        } else {
            html.removeClass('left-sidebar-top');
        }
    });

    //LEFT SIDEBAR FIXED 
    left_sidebar_fixed.on('change', function(event) {

        if (left_sidebar_fixed.is(':checked')) {
            fixedStyle();
            html.removeClass('left-sidebar-scroll');
        } else {
            html.addClass('left-sidebar-scroll');
        }
    });



    // LEFT-SIDEBAR COLLAPSED
    left_sidebar_collapse.on('change', function(event) {

        if (left_sidebar_collapse.is(':checked')) {
            html.addClass('left-sidebar-collapsed');

        } else {
            html.removeClass('left-sidebar-collapsed');
        }
    });


    // LEFT-SIDEBAR OVER
    left_sidebar_over.on('change', function(event) {

        if (left_sidebar_over.is(':checked')) {
            html.addClass('left-sidebar-over');

        } else {
            html.removeClass('left-sidebar-over');
        }
    });


    // LEFT-SIDEBAR OVER
    left_sidebar_left_lines.on('change', function(event) {

        if (left_sidebar_left_lines.is(':checked')) {
             $("#main-nav").addClass('nav-left-lines');

        } else {
             $("#main-nav").removeClass('nav-left-lines');
        }
    });

    function fixedStyle() {
        html.addClass('fixed').removeClass('scroll');
        header_fixed.prop("checked", true);
    }

    function scrollStyle() {
        html.addClass('scroll').addClass('left-sidebar-scroll').addClass('content-header-scroll').removeClass('fixed');
        header_fixed.removeAttr('checked')
    }

    //COLLAPSED LEFT-SIDEBAR
    $('.left-sidebar-toggle').on('click', function(event) {
        if (left_sidebar_collapse.is(':checked')) {
            left_sidebar_collapse.removeAttr('checked')
        } else {
            left_sidebar_collapse.prop("checked", true);
        }
    });



});



// =-=-=-=-=-=-=-=-=-=-=-
//    P L U G I N S
// =-=-=-=-=-=-=-=-=-=-=-


//APPEAR ANIMATIONS
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
(function($) {

    "use strict";

    $.fn.pluginAnimate = function(opt) {
        var elem = $(this);
        elem.addClass('animated unshown');

        var options = $.extend({}, $.fn.pluginAnimate.defaults, opt);

        elem.appear(function() {
            var delay = (elem.data('animation-delay') ? elem.data('animation-delay') : 1);

            if (delay > 1) elem.css('animation-delay', delay + 'ms');
            elem.addClass(elem.data('animation-name'));

            setTimeout(function() {
                elem.addClass('shown').removeClass('unshown');
            }, delay);

        }, { accX: options.accX, accY: options.accY });

        return this;
    };

    $.fn.pluginAnimate.defaults = {
        accX: 0,
        accY: -50
    };
}(jQuery));


//LOADING BUTTONS
// =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
(function($) {

    "use strict";

    $.fn.loadingButton = function(opt) {

        var btn = $(this);
        var options = $.extend({}, $.fn.loadingButton.defaults, opt);

        //start loading
        if (options.action === "start") {
            if (btn.prop("disabled")) {
                return this;
            }
            if (btn.data('loading-text')) {
                options.text = btn.data('loading-text');
            }
            if (btn.data('loading-icon')) {
                options.icon = btn.data('loading-icon');
            }
            btn.prop("disabled", true)
                .addClass('btn-spinning')
                .attr('data-button-text', btn.text());

            btn.html('<i class="' + options.icon + ' fa-spin" aria-hidden="true"></i> ' + options.text);
        };

        //stop spinning
        if (options.action === "stop") {
            if (btn.hasClass('btn-spinning')) {
                btn.prop("disabled", false).removeClass('btn-spinning').html(btn.attr('data-button-text'));
            }
        }
        return this;
    };

    $.fn.loadingButton.defaults = {
        action: 'start',
        text: 'Loading',
        icon: 'fa fa-spinner'
    };

}(jQuery));