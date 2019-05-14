jQuery( document ).ready( function( $ ) {
    quicklinksvisible = false;

    $("#toggle-quick-links, #toggle-quick-links-inside").click(function() {
        $("#quick-links").toggleClass("closed");
        $("#site-container-outer").toggleClass("quick-links-active");

        if (quicklinksvisible) {
            $("#toggle-quick-links, #toggle-quick-links-inside").attr("aria-expanded", false);
            $("#toggle-quick-links .label, #toggle-quick-links-inside .label").text("Expand Quick Links");
        } else {
            $("#toggle-quick-links, #toggle-quick-links-inside").attr("aria-expanded", true);
            $("#toggle-quick-links .label, #toggle-quick-links-inside .label").text("Collapse Quick Links");
        }

        quicklinksvisible = !quicklinksvisible;
    });
} );