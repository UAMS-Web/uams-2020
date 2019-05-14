
<?php
//* Adding search form
add_action('genesis_before', 'uams_toggle_search', 4);
function uams_toggle_search(){
 echo '<div id="header-search" class="closed">' . "\n";
 echo '<div class="inner-container">' . "\n";
 echo '<button type="button" id="toggle-search-inside" aria-controls="header-search" aria-expanded="false" aria-label="Toggle Search">' . "\n";
 echo '<span class="sr-only label">Expand Search</span>' . "\n";
 echo '<span class="fas fa-times fa-lg fa-fw"></span>' . "\n";
 echo '</button>' . "\n";

 echo get_search_form();

 echo '</div>' . "\n";
 echo '</div>' . "\n";
}