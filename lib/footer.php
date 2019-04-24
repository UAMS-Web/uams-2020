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
	echo '<a href="/" class="logo">';
	echo '<img src="/wp-content/themes/uams-2020/assets/svg/UAMS-Logo_Qualifier_Horizontal_White.svg" alt="University of Arkansas for Medical Sciences logo">';
    echo '<span class="sr-only">University of Arkansas for Medical Sciences</span>';
    echo '</a>';
    
	echo '<div itemscope="" itemtype="http://schema.org/LocalBusiness" class="schema">';
	echo '<span itemprop="name" class="sr-only">University of Arkansas for Medical Sciences</span>';
	echo '<div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">';
	echo '<span itemprop="streetAddress">4301 West Markham Street</span>, ';
	echo '<span itemprop="addressLocality">Little Rock</span>, ';
	echo '<span itemprop="addressRegion">Arkansas</span>, ';
	echo '<span itemprop="postalCode">72205</span>';
    echo '</div>';
	echo '<div>Phone: <span itemprop="telephone">501-686-7000</span></div>';
    echo '</div>';

    echo '<ul class="nav" role="navigation">';
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