$(function () {
    $("img:not(.just)").smartLazyLoad({offsetLoad: 100, opacityShow: true});

    $('.slider').slick();
    $(".good-wrap .about span").click(function (e) {
        e.preventDefault();
        e.stopPropagation();
        $(".good-wrap.open").css({"margin-bottom": ""}).removeClass("open").find(".expend.open").removeClass("open");

        var element = $(this).closest(".about").find(".expend");
        var good = $(this).closest(".good-wrap");

        var isOk = element.is(".open");

        if (isOk) {
            element.removeClass("open");
            good.removeClass("open");
        } else {
            element.addClass("open");
            var oh = element.outerHeight();
            good.css({"margin-bottom": -1 * oh + "px"});
            good.addClass("open");
        }
    });

    $(".good-wrap.open").click(function (e) {
        e.stopPropagation();
    });

    $("body").click(function (e) {
        $(".good-wrap.open").css({"margin-bottom": ""}).removeClass("open").find(".expend.open").removeClass("open");
    });


    new SimpleBar($('.js-target-simplebar')[0]);


    $(".to-cart").click(function () {
        $('#imageView').css({display: "none"})
        var element = $(this).closest(".good");

        var name = $.trim(element.find(".name").html());
        var article = $.trim(element.find(".article").html());
        var price = element.find(".price").html();
        var priceContainer = element.find(".old-price");

        var oldPrice = "";
        if (priceContainer.length > 0) {
            oldPrice = priceContainer.html();
        }

        var img = element.find(".image img").attr("src");

        $(".form-order .name").html(name);
        $(".form-order .article-price .article").html(article);
        $(".form-order .article-price .old").html(oldPrice);
        $(".form-order .article-price .new").html(price);
        $(".form-order input.image").val(img);

        $(".form-order").removeClass("hide").addClass("active");
        $("body").addClass("overflow");
    });


    $(".js-target-view-video").click(function () {
        var dataVideo = $(this).attr("data-video");
        var content = "";

        if (dataVideo) {
            if (dataVideo.indexOf('youtube') !== -1) {
                //console.log("Youtube " + dataVideo);
                content = '<iframe src="' + dataVideo + '">\n' +
                    '    Ваш браузер не поддерживает плавающие фреймы!\n' +
                    ' </iframe>';
            } else {
                //console.log("Native " + dataVideo);
                content = '<video src="' + dataVideo + '" autoplay></video>';
            }
            $(".modal-container.video").show().css({display: "flex"}).find(".modal-content").html(content);
        }
    });

    $(".modal-container").click(function (e) {
        $(this).hide();
        if ($(this).is(".video")) {
            $(this).find(".modal-content").html("");
        }
    });

    $(".modal-container .modal-content").click(function (e) {
        //$(this).html(" ");
        e.stopPropagation();
    });


    $(".good-category .content-grid .good-wrap .image").click(function (event) {
        if(window.innerWidth < 768) {
            var element = $(this).closest(".good");

            var imageView = $("#imageView");

            var name = $.trim(element.find(".name").html());
            var article = $.trim(element.find(".article").html());
            var price = element.find(".price").html();
            var priceContainer = element.find(".old-price");
            var hit = element.find(".hit") ? element.find(".hit").html() : false;
            var sale = element.find(".sale") ? element.find(".sale").html() : false;

            var oldPrice = "";
            if (priceContainer.length > 0) {
                oldPrice = priceContainer.html();
            }

            var img = element.find(".image img").attr("src");

            imageView.find(".name").html(name);
            imageView.find(".name-line__name").html(name);

            imageView.find(".article").html(article);
            imageView.find(".article-line__article").html(article);

            imageView.find(".old-price").html(oldPrice);
            imageView.find(".article-line__old-price").html(oldPrice);

            imageView.find(".price").html(price);
            imageView.find(".name-line__price").html(price);

            if (hit) {
                imageView.find(".hit").html(hit).show();
            } else {
                imageView.find(".hit").hide();
            }

            if (sale) {
                imageView.find(".sale").html(sale).show();
            } else {
                imageView.find(".sale").hide();
            }
            imageView.css({"display": 'flex'});
            imageView.find(".good-image").attr({"src": img}).css({"opacity": "1"});
        } else {
            var imageView = $("#imageView-desctop");
            var img =  $(this).find("img").attr("src");
            imageView.css({"display": 'flex'});
            imageView.find(".image").attr({"src": img}).css({"opacity": "1"});
        }
    });

    $("#imageView-desctop").find(".close").click(function () {
        $("#imageView-desctop").css({"display": 'none'});
    });


    $("#imageView").find(".close").click(function () {
        $("#imageView").css({"display": 'none'});
    });

    $("#imageView").find(".good-image").click(function () {
        $("#imageView").css({"display": 'none'});
    });
});