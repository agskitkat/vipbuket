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
        var quantityElement = $(block).find(".quantity-number");
        var last_quantity = +$(quantityElement).attr("data-quantity");
        var new_quantity = +last_quantity;
        if(1 >= (last_quantity + direction)) {
            new_quantity = 1;
        } else {
            new_quantity += direction;
        }
        $(quantityElement).attr({"data-quantity":new_quantity}).html(new_quantity);
    }

});