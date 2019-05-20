jQuery( document ).ready( function( $ ) {
    searchvisible = false;
    $("#toggle-search, #toggle-search-inside").click(function() {
        $("#header-search").toggleClass("closed");

        if (searchvisible) {
            $("#toggle-search, #toggle-search-inside").attr("aria-expanded", false);
            $("#toggle-search .label, #toggle-search-inside .label").text("Expand Quick Links");
        } else {
            $("#toggle-search, #toggle-search-inside").attr("aria-expanded", true);
            $("#toggle-search .label, #toggle-search-inside .label").text("Collapse Quick Links");
            $("#uams-search-bar").focus();
        }

        if (!searchvisible) {
            $("#uams-search-bar").focus();
        }
        searchvisible = !searchvisible;
        $(this).toggleClass("active");
    });
} );