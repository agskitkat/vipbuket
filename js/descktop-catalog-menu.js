$(function() {
    var ODCM = {
        names: {
            btn: "#open-desktop-catalog-menu",
            odcm: "#odcm",
        },
        init: function() {
            $(this.names.btn).click(function() {
                var odcm =  $(this.names.odcm);
                $(odcm).toggleClass("open");
                $(this.names.btn).toggleClass("open");
            }.bind(this));
        }
    }
    ODCM.init();
});