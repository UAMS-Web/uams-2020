jQuery( document ).ready( function( $ ) {
    searchvisible = false;
    quicklinksvisible = false;
    delay = 500; // amount of time to delay while open tray closes before opening the other. Match this to the CSS animation duration.

    function togglesearchopen() {
        $("#header-search").toggleClass("closed"); // toggle closed class on search tray
        $("#toggle-search").toggleClass("active"); // toggle active class on toggle button
        $("#toggle-search, #toggle-search-inside").attr("aria-expanded", true); // set aria-expanded to true
        $("#toggle-search .label, #toggle-search-inside .label").text("Collapse Search"); // change label to "Collapse Search"
        $("#uams-search-bar").focus(); // focus on search bar
        searchvisible = true;
    }

    function togglesearchclosed() {
        $("#header-search").toggleClass("closed"); // toggle closed class on search tray
        $("#toggle-search").toggleClass("active"); // toggle active class on main toggle button
        $("#toggle-search, #toggle-search-inside").attr("aria-expanded", false); // set aria-expanded to false
        $("#toggle-search .label, #toggle-search-inside .label").text("Expand Search"); // change label to "Expand Search"
        searchvisible = false;
    }

    function togglequicklinksopen() {
        $("#quick-links").toggleClass("closed"); // toggle closed class on quick links tray
        $("#site-container-outer").toggleClass("quick-links-active"); // toggle quick-links-active class on div#site-container-outer
        $("#toggle-quick-links").toggleClass("active"); // toggle active class on main toggle button
        $("#toggle-quick-links, #toggle-quick-links-inside").attr("aria-expanded", true); // set aria-expanded to true
        $("#toggle-quick-links .label, #toggle-quick-links-inside .label").text("Collapse Quick Links"); // change label to "Collapse Quick Links"
        quicklinksvisible = true;
    }

    function togglequicklinksclosed() {
        $("#quick-links").toggleClass("closed"); // toggle closed class on quick links tray
        $("#site-container-outer").toggleClass("quick-links-active"); // toggle quick-links-active class on div#site-container-outer
        $("#toggle-quick-links").toggleClass("active"); // toggle active class on main toggle button
        $("#toggle-quick-links, #toggle-quick-links-inside").attr("aria-expanded", false); // set aria-expanded to false
        $("#toggle-quick-links .label, #toggle-quick-links-inside .label").text("Expand Quick Links"); // change label to "Expand Quick Links"
        quicklinksvisible = false;
    }

    function togglesearch() {
        if (quicklinksvisible && !searchvisible) {
            togglequicklinksclosed();

            setTimeout(
                function() 
                {
                    togglesearchopen();
                }, delay);
        } else if (searchvisible) {
            togglesearchclosed();
        } else {
            togglesearchopen();
        }
    }

    function togglequicklinks() {
        if (searchvisible && !quicklinksvisible) {
            togglesearchclosed();

            setTimeout(
                function() 
                {
                    togglequicklinksopen();
                }, delay);
        } else if (quicklinksvisible) {
            togglequicklinksclosed();
        } else {
            togglequicklinksopen();
        }
    }

    $("#toggle-search, #toggle-search-inside").click(togglesearch);
    $("#toggle-quick-links, #toggle-quick-links-inside").click(togglequicklinks);
} );