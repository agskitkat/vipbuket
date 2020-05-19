function showModal(targetModal) {
    $("body").css({"overflow":"hidden"});
    $("#"+targetModal).addClass("visible").removeClass("hide");
}

function hideModal(targetModal) {
    $("body").css({"overflow":"visible"});
    $("#"+targetModal)
        .removeClass("visible")
        .addClass("hide")
        .find('iframe')
        .remove();
}

$(function() {
    $(".s-modal-target").each(function(val, key) {
        var targetModal = $(val).attr("data-target-modal");
        showModal(targetModal);
    });

    $(".js-close-modal").click(function () {
        var targetModal = $(this).closest(".s-modal").attr("id");
        hideModal(targetModal);
    });

    $('.js-play-youtube').click(function () {
        var dataVideo = $(this).attr("data-video");
        $('.iframe-block').html('<iframe src="" allow="encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>');
        $("#video-play").find("iframe").attr({'src': dataVideo});
        showModal("video-play");
    });
});