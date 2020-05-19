$(function () {
    $('.slider-static-width').slick({
        lazyLoad: 'progressive',
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 1,
        centerMode: false,
        variableWidth: true,
        arrows: true,
        responsive: [
            {
                breakpoint: 760,
                settings: {
                    arrows: false,
                }
            }
        ]
    });
});