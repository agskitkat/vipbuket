$(function() {

    if(window.location.hash .indexOf('item-') !== -1 ) {
        
        var $scrollTo = $(window.location.hash);

        $('body,html').animate({
            scrollTop: $($scrollTo).offset().top
        }, 200);
    }

});