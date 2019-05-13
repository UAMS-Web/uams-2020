<?php
/**
 * Custom Header
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

// Custom Header
// remove_action( 'wp_head', 'genesis_custom_header_style' );
remove_action( 'genesis_header', 'genesis_do_header' );

//Does this need to move to nav.php and tie into function uamswp_navbar_brand_markup?
// Here goes the logo in Header -- need to update this with SVG magic?
add_action( 'genesis_header', 'ursidae_site_image', 5 );
 
function ursidae_site_image() {
	$header_image = '<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_main_white.svg" alt="University of Arkansas for Medical Sciences Logo" />';
	?>
	<!-- /* Begin Title / Logo */  -->
	<div class="global-title">
	<?php printf( '<a href="%s" class="navbar-brand">%s<span class="sr-only">%s</span></a>', uams_get_home_link(), $header_image, 'University of Arkansas for Medical Sciences' ); ?>
	<div class="navbar-subbrand">

	<?php
	// If it's a subsection
	if (uamswp_nav_subsection()){
	echo '<a class="parent" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.uams_site_title().'<span class="sr-only">:</span></a>';
	echo '<a class="title" href="'. get_the_permalink( uamswp_nav_subsection() ) .'">'. get_the_title(uamswp_nav_subsection()) .'</a>';
	} else {
	// If it's a regular old homepage
	echo '<a class="title" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.uams_site_title().'</a>';
	}
  
	// If it's an institute or other split title entity, separate the title descriptor (often the donor) and the functional title (often the center/institute of X) into two separate spans as below.
	// Use three spaces in site title to indicate desire to split the title. Find/replace those three spaces 
	//echo '<a class="title-split" href="javascript:void(0)"><span class="title-descriptor">Jackson T. Stephens</span> <span class="title-function">Spine and Neurosciences Institute</span></a>';
	?>

		</div>
	</div>
	<!-- /* End Title / Logo */ -->

	<!-- /* Begin Right Navbar */ -->
	<nav class="header-nav" aria-label="Resource Navigation">
		<div class="collapse navbar-collapse" id="nav-secondary">
			<ul class="nav">
				<?php if (('uams' == uams_get_site_info()['site']) || ('institute' == uams_get_site_info()['site'])) { ?>
				<!-- Options - uams -->
				<li class="nav-item">
					<a class="nav-link" href="https://www.uams.edu/">UAMS.edu</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://mychart.uamshealth.com/">MyChart</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://giving.uams.edu/">Giving</a>
				</li>
				<!-- End right nav -->
				<?php } elseif ('uamshealth' == uams_get_site_info()['site']) { ?>
				<!-- Options - uamshealth -->
				<li class="nav-item">
					<a class="nav-link" href="https://www.uams.edu/">UAMS.edu</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://mychart.uamshealth.com/">MyChart</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://giving.uams.edu/">Giving</a>
				</li>
				<!-- End right nav -->
				<?php } elseif ('inside' == uams_get_site_info()['site']) { ?>
				<!-- Options - inside -->
				<li class="nav-item">
					<a class="nav-link" href="https://www.uams.edu/">UAMS.edu</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://mychart.uamshealth.com/">MyChart</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="http://giving.uams.edu/">Giving</a>
				</li>
				<!-- End right nav -->
				<?php } ?>
			</ul>
		</div>
		<ul class="nav resource-nav" id="nav-resource">
			<!-- uamshealth only -->
			<li class="nav-item">
				<a class="nav-link" href="javascript:void(0)"><span class="fas fa-ambulance fa-lg"></span><span class="sr-only">Emergency Room</span></a>
			</li>
			<!-- uamshealth only -->
			<li class="nav-item">
				<a class="nav-link" id="toggle-search" href="javascript:void(0)"><span class="fas fa-search fa-lg"></span><span class="sr-only">Open Search</span></a>
			</li>
		</ul>

		<!-- // Hiding this button until Quick Links is created. -->
		<!-- <button class="navbar-toggler quick-links-toggler" type="button" data-toggle="collapse" data-target="#nav-quick-links" aria-controls="quick-links" aria-expanded="false" aria-label="Toggle Quick Links navigation">
		<span class="sr-only">Expand Quick Links</span>
		<span class="fas fa-bars fa-lg"></span>
		</button> -->

		<!-- // The data-target and aria-controls may need to be dynamically defined. -->
		<button class="navbar-toggler mobile-menu-toggler" type="button" data-toggle="collapse" data-target="#nav-primary" aria-controls="nav-primary" aria-expanded="false" aria-label="Toggle Primary navigation">
			<span class="sr-only">Expand Primary Nav</span>
			<span class="fas fa-bars fa-lg"></span>
		</button>
	
	</nav>
	<!-- /* End Right Navbar */ -->
	<?php
}