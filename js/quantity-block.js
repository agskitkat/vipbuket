$(function() {

    $(".good-quantity-block").each(function (key, elem) {


        $(elem).find(".js-increment").click( function() {
            updateQuantity(elem,1);
        });

        $(elem).find(".js-decrement").click( function() {
            updateQuantity(elem,-1);
        });

    });

    function updateQuantity(block, direction) {
        var price = $(block).find(".quantity-price-item");
        var quantityElement = $(block).find(".quantity-number");
        var last_quantity = +$(quantityElement).attr("data-quantity");
        var new_quantity = +last_quantity;
        if(1 >= (last_quantity + direction)) {
            new_quantity = 1;
        } else {
            new_quantity += direction;
        }
        var new_price = new_quantity * + $(price).attr('data-one-item-price');
        $(price).html(new_price + " ₽");
        $(quantityElement).attr({"data-quantity":new_quantity}).html(new_quantity);
    }

});