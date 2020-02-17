$(function () {
    $('.image-slider').slick({
        dots: true,
        slidesToShow: 1,
        arrows: false,
        asNavFor: '.image-slider-control',
        infinite: true,

    });

    $('.image-slider-control').slick({
        dots: false,
        vertical: true,
        slidesToShow: 4,
        slidesToScroll: 1,
        verticalSwiping: true,
        infinite: true,
        arrows: false,
        focusOnSelect: true,
        asNavFor: '.image-slider',
    });
});