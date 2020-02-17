$(function() {
    $(".form-block .close, .form-order").click(function(e) {
        e.stopPropagation();
        $(this).closest(".form-order").addClass("hide").removeClass("active");
        $("body").removeClass("overflow");
    });

    $(".form-order .form-block").click(function(e) {
        e.stopPropagation();
    });

    var form = $(".form-order");
    form.find("input#postcard").change(function(){
        var inputs = form.find('.order-options .inputs');
        if( !$(this).prop('checked')) {
            inputs.hide();
        } else {
            inputs.show();
        }
    });

    function validateEmail(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    }

    var lock = false;
    var old_button_text = "";


    form.find("#do_order").click(function(event) {

        event.preventDefault();
        event.stopPropagation();
        var btn = $(this);
        if(!lock) {

            old_button_text = btn.html();
            btn.html(btn.attr("data-wait-text"));

            lock = true;
            var ar = $(form).find('.form__form').serializeArray();
            var error = "";

            $.each(ar, function(key , val){
                console.log(val.name);

                if(val.name === 'user_name' && !val.value) {
                    error += "Заполните имя<br>";
                }

                if(val.name === 'user_phone' && !val.value) {
                    error += "Заполните телефон<br>";
                }

                if(val.name === 'user_email' && !validateEmail(val.value)) {
                    error += "Заполните Email<br>";
                }

                if(val.name === 'user_date' && !val.value) {
                    error += "Заполните дату<br>";
                }

                if(val.name === 'user_addr' && !val.value) {
                    error += "Заполните адрес<br>";
                }
            });

            var good = $(form).find('.good');
            var itemName = good.find('.name').html();
            var itemArticle = good.find('.article').html();
            var itemPrice = good.find('.price .new').html();
            var itemImg = good.find('input.image').val();

            console.log(ar);

            if(error === "") {
                var data = {
                    action: 'order',
                    data: ar,
                    item: {
                        name: itemName,
                        article: itemArticle,
                        price: itemPrice,
                        img: itemImg
                    }
                };
                // с версии 2.8 'ajaxurl' всегда определен в админке
                $.post("/wp-admin/admin-ajax.php", data, function (response) {
                    lock = false;
                    btn.html(old_button_text);
                    form.addClass("hide").removeClass("active");
                    $('.modal-container.order-complite').css({display: "flex"});
                    //console.log('Получено с сервера: ' + response);
                });

            } else {
                var msg = "<span>Проверьте правильность заполнения формы:</span><br>";
                $(this).closest(".form-order").find(".errors").html(msg + error);
                lock = false;
                btn.html(old_button_text);
            }
        }
    });
});