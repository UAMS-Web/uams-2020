jQuery( document ).ready( function( $ ) {
    quicklinksvisible = false;
    $(".quick-links-toggler").click(function() {
        $("#quick-links").toggleClass("closed");
        quicklinksvisible = !quicklinksvisible;
    });
} );