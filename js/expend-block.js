$(function() {

    $('.good-wrap .good .mobile-expend-block').each(function (k,v) {
        if($(v).find('.content').innerHeight() <= 38) {
            $(v).find('.expend-fade').hide();
            $(v).find('.expend-button').hide();
        }
    });

    $('.good-wrap .good .mobile-expend-block').click(function(e) {
        console.log('click', this.isOpen);
        if(!this.isOpen) {
            $(this).find('.expend-text').addClass('show').removeClass('hide');
            $(this).find('.expend-fade').addClass('hide').removeClass('show');
            this.isOpen = true;
        } else {
            $(this).find('.expend-text').addClass('hide').removeClass('show');
            $(this).find('.expend-fade').addClass('show').removeClass('hide');
            this.isOpen = false;
        }
    });
});