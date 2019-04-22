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
	echo '<div class="creds"><p>';
	echo 'Copyright &copy; 2008 - ';
    echo date('Y');
    echo '<div itemscope="" itemtype="http://schema.org/LocalBusiness"><span itemprop="name">University of Arkansas for Medical Sciences</span>
    <div itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress"><span itemprop="streetAddress">4301 West Markham Street</span>, <span itemprop="addressLocality">Little Rock</span>, <span itemprop="addressRegion">Arkansas</span>, <span itemprop="postalCode">72205</span>
    </div><div>Phone: <span itemprop="telephone">501-686-7000</span></div>';
    echo ' All Rights Reserved | <a href="/disclaimer/">Disclaimer</a> | <a href="/termsofuse/">Terms of Use</a> | <a href="/privacy/">Privacy</a> | <a href="/sitemap/">Site Map</a>';
	echo '</p></div>';
}