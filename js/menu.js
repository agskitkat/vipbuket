$(function () {
    $(".js-event-click-view-menu").click(function () {
        window.location.hash = "menu--open";
    });

    $(".js-event-click-hide-menu").click(function () {
        window.location.hash = "";
    });

    $("#js-target-mobile-menu .parent-category > li ").click(function (e) {
        var child = $(this).find('.child');
        console.log(child);
        if(child.length) {
            var id = $(this).find(".child").attr("id");
            window.location.hash = "menu--" + id;
        } else {
            window.location.href =  $(this).find("> span").attr('href');
        }
    });

    $("#js-target-mobile-menu .child .js-event-click-hide-sub-menu").click(function (e) {
        e.stopPropagation();
        // var c = $(this).closest(".child");
        //c.removeClass("open").delay(300).queue(function(){ $(this).hide() });
        window.location.hash = "menu--level-close";
    });

    $(window).bind('hashchange', function () {
        var hash = location.hash;
        var arH = hash.split("--");
        if (arH.length === 2) {
            var id = arH[1];
            console.log(id);
            if (id === "open" || id === "level-close") {
                console.log("OPEN");
                $("#js-target-mobile-menu").addClass("open");
                //console.log( $("#js-target-mobile-menu"));
            }

            if (id === "level-close" || !id || id === "open") {
                var element = $(".parent-category .child.open");
                console.log(element);
                element.removeClass("open").delay(300).queue(function () {
                    $(this).hide()
                });
            }

            if (id) {
                var child = $("#" + id);
                child.addClass("open").show();
                if (child.length) {
                    $("#js-target-mobile-menu").addClass("open");
                }
            }
        }
        if (!id) {
            $("#js-target-mobile-menu").removeClass("open").find(".open").removeClass("open");
        }
    });

});