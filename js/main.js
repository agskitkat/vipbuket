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
        $(".good-wrap.open")
            .css({"margin-bottom": ""})
            .removeClass("open")
            .find(".expend.open")
            .removeClass("open");
    });


    //SimpleBar($('.js-target-simplebar')[0]);


    $(".to-cart").click(function () {
        console.log("Add to cart");

        var element = $(this).closest(".good");
        console.log(element);
        if(element.length) {

            var name = $.trim(element.find(".name").html());
            var article = $.trim(element.find(".article").html());
            var price = element.find(".price").html();
            var priceContainer = element.find(".old-price");
            var oldPrice = "";
            if (priceContainer.length > 0) {
                oldPrice = priceContainer.html();
            }

        } else {

            var element = $(this).closest(".full-good");
            console.log("Second", element);
            var name = $.trim(element.find(".good-title").html());
            var article = $.trim(element.find(".good-article").html());
            var price = $.trim(element.find(".good-price").html());
            var priceContainer = element.find(".good-old-price");
            var oldPrice = "";
            if(priceContainer.length > 0) {
                oldPrice = priceContainer.html();
            }

        }
        //document.body.setAttribute("","");

        var img = element.find(".image img").attr("src");

        $(".form-order .name").html(name);
        $(".form-order .article-price .article").html(article);
        $(".form-order .article-price .old").html(oldPrice);
        $(".form-order .article-price .new").html(price);
        $(".form-order input.image").val(img);

        $("#imageView").removeClass("active").addClass("hide").hide();
        $(".form-order").removeClass("hide").addClass("active");
        $("body").addClass("overflow");

    });


    $(".js-target-view-video").click(function () {
        console.log("Video view");
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
        console.log("modal-container");
        $(this).hide();
        $("body").removeClass("overflow");
        if ($(this).is(".video")) {
            $(this).find(".modal-content").html("");
        }
    });

    $(".modal-container .modal-content").click(function (e) {
        //$(this).html(" ");
        e.stopPropagation();
    });

    $(".good-category .content-grid .good-wrap .image").click(function (event) {
        console.log("Grid image click");

        var element = $(this).closest(".good-wrap");

        var name = $.trim(element.find(".name").html());
        var article = $.trim(element.find(".article").html());
        var price = element.find(".price").html();
        var oldPrice = element.find(".old-price").html();
        var sale = element.find(".sale").html();
        var hit = element.find(".hit");
        var content = element.find(".content").html();
        var image = $(this).find("img");

        var imageView = $("#imageView");

        console.log(name, image.attr('src'));

        imageView.find(".js-name").html(name);
        imageView.find(".js-article").html(article);
        imageView.find(".js-old-price").html(oldPrice);
        imageView.find(".js-price").html(price);
        imageView.find(".js-content").html(content);

        if(sale) {
            imageView.find(".js-sale").show().html(sale);
        } else {
            imageView.find(".js-sale").hide();
        }

        if(hit.length) {
            imageView.find(".js-hit").show();
        } else {
            imageView.find(".js-hit").hide();
        }

        imageView.find(".js-image").attr({"src": image.attr('src')}).css({"opacity": "1"});


        if(window.innerWidth < 768) {
            //$("#imageView").css({"display": 'flex'});
            //$("body").addClass("overflow");
        } else {
           // $("#imageView-desctop").css({"display": 'flex'});
           // $("body").addClass("overflow");
           // $("#imageView-desctop").find(".image").attr({"src": image.attr('src')}).css({"opacity": "1"});
        }
    });

    $(".modal-container").find(".close").click(function () {
        console.log("imageView");
        $(this).closest(".modal-container").css({"display": 'none'});
        $("body").removeClass("overflow");
    });
});