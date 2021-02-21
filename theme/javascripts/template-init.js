/**
 * Created by myii-developer.
 * v-1.2
 * MUNICH TEMPLATE
 */

"use strict";

var app = {

    //NANO-SCROLL LEFT-SIDEBAR
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    nanoscrolls: function(){

        if ( $.isFunction($.fn[ 'nanoScroller' ]) ) {
            $(".nano").nanoScroller();
        };
    },
    //BOOTSTRAP TOOLTIPS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    tooltips: function(){

        if ( $.isFunction($.fn[ 'tooltip' ]) ) {
            $('[data-toggle="tooltip"]').tooltip({ container: 'body' })
        };
    },
    //BOOTSTRAP POPOVER
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    popovers: function(){

        if ( $.isFunction($.fn[ 'popover' ]) ) {
            $('[data-toggle="popover"]').popover({ container: 'body' })
        };
    },

    //APPEAR ANIMATIONS
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    animations: function(){

        if ( $.isFunction($.fn[ 'appear' ]) ) {
            $('[data-animation-name]').each(function() {
                $(this).pluginAnimate()
            });
        };
    },
    peityCharts: function(){
        if ( $.isFunction($.fn[ 'peity' ]) ) {
            $(".pie-chart").peity("pie");
            $(".donut-chart").peity("donut");
            $(".line-chart").peity("line");
            $(".bar-chart").peity("bar");
        };
    }
};


$(function(){

    app.nanoscrolls();
    app.tooltips();
    app.popovers();
    app.animations();
    app.peityCharts();

});