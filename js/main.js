$(function(){
    $("img:not(.just)").smartLazyLoad({offsetLoad:100, opacityShow:true});

    $('.slider').slick();
    $(".good-wrap .about span").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".good-wrap.open").css({"margin-bottom":""}).removeClass("open").find(".expend.open").removeClass("open");

        var element = $(this).closest(".about").find(".expend");
        var good = $(this).closest(".good-wrap");

        var isOk = element.is(".open");

        if(isOk) {
            element.removeClass("open");
            good.removeClass("open");
        } else {
            element.addClass("open");
            var oh = element.outerHeight();
            good.css({"margin-bottom":-1*oh+"px"});
            good.addClass("open");
        }
    });

    $(".good-wrap.open").click(function(e) {
        e.stopPropagation();
    });

    $("body").click(function(e) {
        $(".good-wrap.open").css({"margin-bottom":""}).removeClass("open").find(".expend.open").removeClass("open");
    });

    $(".form-block .close, .form-order").click(function(e) {
        e.stopPropagation();
        $(this).closest(".form-order").addClass("hide").removeClass("active");;
    });

    $(".form-order .form-block").click(function(e) {
        e.stopPropagation();
    });

    $(".to-cart").click(function() {
        var element = $(this).closest(".good");

        var name = $.trim(element.find(".name").html());
        var article = $.trim(element.find(".article").html());
        var price = element.find(".price").html();
        var oldprice = element.find(".old-price").html();

        $(".form-order .name").html(name);
        $(".form-order .article-price .article").html(article);
        $(".form-order .article-price .old").html(oldprice);
        $(".form-order .article-price .new").html(price);

        $(".form-order").removeClass("hide").addClass("active");
    });



});