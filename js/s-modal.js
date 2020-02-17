function showModal(targetModal) {
    $("body").css({"overflow":"hidden"});
    $("#"+targetModal).addClass("visible").removeClass("hide");
}

function hideModal(targetModal) {
    $("body").css({"overflow":"visible"});
    $("#"+targetModal).removeClass("visible").addClass("hide");
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
});