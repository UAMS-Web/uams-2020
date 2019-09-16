
<?php
//* Adding search form
add_action('genesis_before_header', 'uams_toggle_search', 12);
function uams_toggle_search(){
 echo '<section id="header-search" class="closed" aria-label="Search">' . "\n";
 echo '<div class="inner-container">' . "\n";
 echo '<button type="button" id="toggle-search-inside" aria-controls="header-search" aria-expanded="false" title="Close Search">' . "\n";
 echo '<span class="sr-only label">Toggle Search</span>' . "\n";
 echo '<span class="fas fa-times fa-lg fa-fw"></span>' . "\n";
 echo '</button>' . "\n";

 echo get_search_form();

 echo '</div>' . "\n";
 echo '</section>' . "\n";
}