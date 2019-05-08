<?php
/**
 * Custom Footer
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

//* Customize the credits
add_filter( 'genesis_footer_creds_text', 'sp_footer_creds_text' );
function sp_footer_creds_text() {

    // Render this by default, in whatever structure is best.
    // If the site is an EDU site, replace the href value with "https://www.uams.edu".
    // If an SVG asset is uploaded/defined in the custom field, replace the href value with the home URL of the current site.
    // If an SVG asset is uploaded/defined in the custom field, replace the image path with the uploaded SVG asset.
    // The height of the image is controlled by CSS.
    $footer_image = '<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_main-qualifier_horizontal_white.svg" alt="University of Arkansas for Medical Sciences Logo" />';
    printf( '<a href="https://www.uamshealth.com" class="logo">%s<span class="sr-only">University of Arkansas for Medical Sciences</span></a>', $footer_image );
    
    // Render this by default.
    echo '<div itemscope="" itemtype="http://schema.org/LocalBusiness" class="schema">';
	echo '<span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span>';
    echo '<div class="schema-address" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">';

    // Render this by default.
    // Remove the class="sr-only" if any of the Parking Address fields DO have values.
    echo '<strong class="sr-only">Mailing Address:</strong> ';

    // Render this by default.
    // Replace text values if any of the Mailing Address custom fields DO have values.
	echo '<span itemprop="streetAddress">4301 West Markham Street</span>, ';
	echo '<span itemprop="addressLocality">Little Rock</span>, ';
	echo '<span itemprop="addressRegion">Arkansas</span>, ';
	echo '<span itemprop="postalCode">72205</span>';
    echo '</div>';

    // Only render this if any of the custom Parking Address fields DO have values, populating the text values with those custom field values.
    //echo '<div class="schema-address" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">';
    //echo '<strong>Parking Address:</strong> ';
    //echo '<span itemprop="streetAddress">4301 West Markham Street</span>, ';
    //echo '<span itemprop="addressLocality">Little Rock</span>, ';
    //echo '<span itemprop="addressRegion">Arkansas</span>, ';
    //echo '<span itemprop="postalCode">72205</span>';
    //echo '</div>';

    // Render this by default
    echo '<div class="schema-phone">';

    // Render this by default
    // Replace "span" with "strong" if any of the custom Parking Address fields DO have values.
    echo '<span id="footer-phone-label">';

    // Render this by default
    // Replace "Phone" with "Appoinments" if the relevant custom field ("Is Appointment Number" or something) is checked.
    echo 'Phone';

    // Render this by default
    // Replace "span" with "strong" if any of the custom Parking Address fields DO have values.
    echo ':</span> ';

    // Render this by default.
    // Replace both telephone values (text and href) if the custom telephone field has a value.
    // Format user input as "+1-XXX-XXX-XXXX" format in href="tel:" value
    // Format user input as "XXX-XXX-XXXX" format in text value.
    echo '<span itemprop="telephone"><a href="tel:+1-501-686-7000" aria-labelledby="footer-phone-label">501-686-7000</a></span>';

    // Render this if the relevant custom fields have values.
    //echo '<br /><a class="more-phone" href="#">Custom Text for Link</a>';

    // Render this by default
    echo '</div>';
    echo '</div>';

    // Render this by default.
    // Replace social URLs if the relevant custom fields have values.
    echo '<ul class="nav social" role="navigation" aria-label="Social media">';
    echo '<li class="nav-item"><a class="nav-link" href="';
    echo 'https://www.facebook.com/UAMShealth/';
    echo '" target="_blank"><span class="fab fa-facebook"></span><span class="sr-only">Facebook</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="';
    echo 'https://twitter.com/uamshealth';
    echo '" target="_blank"><span class="fab fa-twitter"></span><span class="sr-only">Twitter</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="';
    echo 'https://www.instagram.com/uamshealth/';
    echo '" target="_blank"><span class="fab fa-instagram"></span><span class="sr-only">Instagram</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="';
    echo 'https://www.youtube.com/user/UAMSHealth';
    echo '" target="_blank"><span class="fab fa-youtube"></span><span class="sr-only">YouTube</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="';
    echo 'https://www.linkedin.com/company/uams/';
    echo '" target="_blank"><span class="fab fa-linkedin"></span><span class="sr-only">LinkedIn</span></a></li>';
    echo '<li class="nav-item"><a class="nav-link" href="';
    echo 'https://www.pinterest.com/uamshealth/';
    echo '" target="_blank"><span class="fab fa-pinterest"></span><span class="sr-only">Pinterest</span></a></li>';
    echo '</ul>';

    // Render this by default
    echo '<ul class="nav legal" role="navigation">';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="/disclaimer">Disclaimer</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="/termsofuse">Terms of Use</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="/privacy">Privacy</a>';
    echo '</li>';
    echo '<li class="nav-item">';
    echo '<a class="nav-link" href="/sitemap">Site Map</a>';
    echo '</li>';
    echo '</ul>';
    echo '<p class="copyright">';
	echo '&copy; ';
    echo date('Y');
    echo ' University of Arkansas for Medical Sciences</p>';
}