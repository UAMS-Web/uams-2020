jQuery( document ).ready( function( $ ) {
    searchvisible = false;
    $("#toggle-search").click(function() {
        $("#header-search").toggleClass("closed");
        if (!searchvisible) {
            $("#uams-search-bar").focus();
        }
        searchvisible = !searchvisible;
        $(this).toggleClass("active");
    });
} );