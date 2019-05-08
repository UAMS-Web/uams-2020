
<?php
//* Adding search form
add_action('genesis_before_header', 'uams_toggle_search');
function uams_toggle_search(){
 echo '<div id="header-search" class="closed">' . "\n";
 echo '<div class="inner-container">' . "\n";

 echo get_search_form();

 echo '</div>' . "\n";
 echo '</div>' . "\n";
}