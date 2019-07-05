$(function() {
    $('.js-target-live-search-input').on('keypress', function() {
        var value = $(this).val();
        if(value.length >= 2) {
            var data = {
                action: 'search',
                term: value
            };
            $.post("/wp-admin/admin-ajax.php", data, function (response) {
                //console.log(response);
                $(".live-search__container").html(response);
                $(".live-search").removeClass("hide");
            });
        } else {
            $(".live-search").addClass("hide");
        }
    })
});