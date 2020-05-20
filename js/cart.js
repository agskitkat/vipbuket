$(function () {

    function cartRequest(data) {
        $(".splash-screen").addClass("visible").removeClass("hide");

        return new Promise(function(resolve, reject) {
            $.post("/wp-admin/admin-ajax.php", data, function (response) {
                $(".splash-screen").removeClass("visible").addClass("hide");
                var result = JSON.parse(response);
                if (result) {
                    result = result.result;
                } else {
                    reject();
                }
                updateMiniCart(result);
                updateCart(result);
                resolve(result);
            });
        });

    }

    function updateMiniCart(result) {
        var quantity = result.cart_count ? "В корзине: " + result.cart_count : "Корзина";
        //$("#mini-cart .items").html(quantity);
        $(".header-cart .cart-count").html(result.cart_count);
        $("#mini-cart .cart-info").html(result.sum ? "На сумму " + result.sum + " ₽": "Нет товаров в корзине" );

        var cc = $("#js-cart-count");
        if(result.cart_count > 0) {
            $(cc).removeClass('empty').html(result.cart_count);
        } else {
            $(cc).addClass('empty').html("");
        }
    }

    function updateCart(result) {
        var r = " ₽";
        $('#js-t-cart-header-items-count').html(result.cart_count);

        $('#sum-old-price').html(result.old_sum + r);
        $('#sum-sale').html(result.sale_sum + r);
        $('#sum-price').html(result.sum + r);

        // Обновляем все товары в корзине
        $.each(result.cart, function(key, item){
            var id = item.id;
            //console.log(id, item);
            var elem = $("#cart-item-"+id);
            $(elem).find(".price_actual").html(item.sum.toLocaleString() + r);
            $(elem).find(".view-quantity").html(item.quantity);
            if(item.old_price_sum) {
                $(elem).find(".price_old-price").html(item.old_price_sum.toLocaleString() + r);
            }
        });

    }

    // Добавляем товар в корзину

    /*
    $(".to-cart").click(function () {

        var id = $(this).attr("data-good-id");
        if(!id) {
            return false;
        }

        var data = {
            action: 'addTocart',
            id: id,
            quantity: 1
        };

        cartRequest(data).then(
            function(result){

            },
            function (error) {

            }
        );
    });
    */

    // Удаляем из корзины
    $(".js-action-remove-item-from-cart").click(function () {
        var id = $(this).attr("data-good-id");

        var data = {
            action: 'addTocart',
            id: id,
            quantity: 0
        };

        $("#cart-item-"+id).addClass('removing');

        cartRequest(data).then(
            function(result){
                $("#cart-item-"+id).addClass('delete');
                setTimeout(function() {
                    $("#cart-item-" + id).remove();
                }, 500);
            },
            function (error) {
                $("#cart-item-"+id).removeClass('removing');
            }
         );
    });

    // Плюс и минус
    $('.js-action-good').click(function () {
        var id = $(this).attr("data-good-id");
        var direction = $(this).is('.decrement')?-1:1
        var elem_quantity = $("#cart-item-"+id).find(".view-quantity");
        var old_quantity = elem_quantity.html();

        //console.log(old_quantity, direction);
        // Если пытаемся положить в корзину 0
        if(+old_quantity === 1 && direction < 0) {
            return false;
        }

        var new_quantity = +old_quantity + direction;
        elem_quantity.html(new_quantity);

        var data = {
            action: 'addTocart',
            id: id,
            quantity: new_quantity
        };

        cartRequest(data).then(
            function(result){

            },
            function (error) {

            }
        );
    })


    // на странице одного товара (карточка товара)
    $(".js-action-add-to-cart").click(function() {
        var redirect = $(this).attr("data-redirect");

        if(window.innerWidth < 760 && !redirect) {
            // Открыть модальное окно для добавления товара в мобиле
            openModalToAdd( $(this).attr("data-good") );
        }


        // Добавить товар
        good = JSON.parse( $(this).attr("data-good") );

        var quantity = $(this)
            .closest(".full-good__container")
            .find(".quantity-number")
            .attr("data-quantity");

        if(!quantity) {
            quantity = 1;
        }

        cartRequest({
            action: 'addTocart',
            method: "addition",
            id: good.id,
            quantity: quantity
        })
        .then(
            function(result){
                if(redirect) {
                    window.location.href = redirect;
                }
            },
            function (error) {

            }
        );

    });


    // Добавление товара через модальное окно
    $("#atc-quantity").attrchange({
        callback: function () {
            var id = $("#add-to-cart-mobile").attr("data-id");
            var quantity = $("#atc-quantity").attr("data-quantity");

            cartRequest({
                action: 'addTocart',
                id: id,
                quantity: quantity
            })
            .then(
                function(result){

                },
                function (error) {

                }
            );

        }
    });

    // Модальное окно для добавления в корзину на мобиле
    function openModalToAdd(good) {
        good = JSON.parse(good);
        console.log(good);

        var data_id = good.id;
        var atc_image = good.img;
        var atc_name = good.name;
        var atc_price = good.price;
        var atc_one_price = good.price;
        var atc_old_price = good.old_price;
        var atc_quantity = good.quantity?good.quantity:1;
        var atc_article = good.article;

        if(good.sale) {
            $("#atc-sale").removeClass("hide").html("-"+good.sale+"%");
        } else {
            $("#atc-sale").addClass("hide");
        }

        if(good.new) {
            $("#atc-new").removeClass("hide");
        } else {
            $("#atc-new").addClass("hide");
        }

        if(good.hit) {
            $("#atc-hit").removeClass("new");
        } else {
            $("#atc-hit").addClass("new");
        }

        $("#add-to-cart-mobile").attr({"data-id":data_id});
        $("#atc-image").attr({src:atc_image});
        $("#atc-name").html(atc_name);
        $("#atc-article").html(atc_article);
        $("#atc-price").html(atc_price + " ₽");
        $("#atc-one-price").html(atc_one_price + " ₽");
        $("#atc-one-price").attr({'data-one-item-price': atc_one_price});

        if(atc_old_price) {
            $("#atc-old-price").show().html(atc_old_price + " ₽");
        } else {
            $("#atc-old-price").hide();
        }

        $("#atc-quantity").html(+atc_quantity).attr({"data-quantity":+atc_quantity});

        // Показываем
        showModal("add-to-cart-mobile");
    }



});