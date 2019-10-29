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
add_action( 'genesis_header', 'uamswp_site_image', 5 );
 
function uamswp_site_image() {
	if ('uamshealth' == uams_get_site_info()['site']) {
		$header_image = '<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark.svg" alt="UAMS Health Logo" />';
		$header_image_link = 'https://www.uamshealth.com';
		$header_image_text = 'UAMS Health';
	}
	else {
		$header_image = '<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_main_dark.svg" alt="University of Arkansas for Medical Sciences Logo" />';
		$header_image_link = 'https://www.uams.edu';
		$header_image_text = 'University of Arkansas for Medical Sciences';
	}
	?>
	<!-- /* Begin Title / Logo */  -->
	<div class="global-title">

	<?php
	if ('uamshealth' == uams_get_site_info()['site'] && 'main' == uams_get_site_info()['subsite'] && !uamswp_nav_subsection()) { // If it's the main UAMS Health site and not a subsection
		printf( '<a href="' . $header_image_link . '" class="navbar-brand no-subbrand">%s<span class="sr-only">%s</span></a>', $header_image, $header_image_text );
	} else {
		printf( '<a href="' . $header_image_link . '" class="navbar-brand">%s<span class="sr-only">%s</span></a>', $header_image, $header_image_text );
		echo '<div class="navbar-subbrand">';

		if ('uamshealth' == uams_get_site_info()['site'] && 'main' == uams_get_site_info()['subsite'] && uamswp_nav_subsection()) { // If it's a subsection on the main UAMS Health site
			echo '<a class="title" href="'. get_the_permalink( uamswp_nav_subsection() ) .'">'. get_the_title(uamswp_nav_subsection()) .'</a>';
		} elseif (uamswp_nav_subsection()) { // If it's a subsection on any other site
			echo '<a class="parent" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.uams_site_title().'</a><span class="sr-only">: </span>';
			echo '<a class="title" href="'. get_the_permalink( uamswp_nav_subsection() ) .'">'. get_the_title(uamswp_nav_subsection()) .'</a>';
		} elseif ('inside' == uams_get_site_info()['site'] && 'main' !== uams_get_site_info()['subsite']) {
			switch_to_blog(1);
			$site_title = get_bloginfo( 'name' );
			restore_current_blog();
			echo '<a class="parent" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( network_site_url() ).'">'.$site_title.'<span class="sr-only">:</span></a>';
			echo '<a class="title" href="'. esc_url( home_url( '/' ) ) .'">'. get_bloginfo( 'name' ) .'</a>';
		} else {
		// If it's a regular old homepage
			echo '<a class="title" title="'.esc_attr( get_bloginfo( 'description' ) ).'" href="'.esc_url( home_url( '/' ) ).'">'.uams_site_title().'</a>';
		}

		echo '</div>';
	}
  
	// If it's an institute or other split title entity, separate the title descriptor (often the donor) and the functional title (often the center/institute of X) into two separate spans as below.
	// Use three spaces in site title to indicate desire to split the title. Find/replace those three spaces 
	//echo '<a class="title-split" href="javascript:void(0)"><span class="title-descriptor">Jackson T. Stephens</span> <span class="title-function">Spine and Neurosciences Institute</span></a>';
	?>
	
	</div>
	<!-- /* End Title / Logo */ -->

	<!-- /* Begin Right Navbar */ -->
	<nav class="header-nav" aria-label="Resource Navigation">
		<div class="collapse navbar-collapse" id="nav-secondary">
			<ul class="nav">
				<?php if (('uams' == uams_get_site_info()['site']) || ('institute' == uams_get_site_info()['site']) || empty(uams_get_site_info()['site'])) { ?>
				<!-- Options - uams -->
				<li class="nav-item">
					<a class="nav-link" href="https://www.uamshealth.com/">UAMS Health</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="https://jobs.uams.edu/">Jobs</a>
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
					<a class="nav-link" href="https://www.uamshealth.com/">UAMS Health</a>
				</li>
				<!-- End right nav -->
				<?php } ?>
			</ul>
		</div>
		<ul class="nav resource-nav" id="nav-resource">
			<?php if ('uamshealth' == uams_get_site_info()['site']) { ?>
			<!-- uamshealth only -->
			<li class="nav-item">
				<a class="nav-link emergency-link" href="javascript:void(0)" aria-label="Emergency Room"><span class="fas fa-ambulance fa-lg"></span><span class="sr-only">Emergency Room</span></a>
			</li>
			<? } // endif ?>
			<li class="nav-item">
				<button class="search-toggler" type="button" id="toggle-search" aria-controls="header-search" aria-expanded="false" title="Toggle Search">
					<span class="sr-only label">Toggle Search</span>
					<span class="fas fa-search fa-lg fa-fw"></span>
					<span class="fas fa-times fa-lg fa-fw"></span>
				</button>
			</li>
		</ul>

		<?php
		// Removing Quick Links toggler via comments in case we decide to bring it back.
		// <button class="quick-links-toggler" type="button" id="toggle-quick-links" aria-controls="quick-links" aria-expanded="false" title="Toggle Quick Links navigation">
		// 	<span class="sr-only label">Toggle Quick Links</span>
		// 	<span class="fas fa-bars fa-lg fa-fw"></span>
		// 	<span class="fas fa-times fa-lg fa-fw"></span>
		// </button>
		?>

		<!-- // The data-target and aria-controls may need to be dynamically defined. -->
		<button class="navbar-toggler mobile-menu-toggler" type="button" data-toggle="collapse" data-target="#genesis-nav-primary" aria-controls="genesis-nav-primary" aria-expanded="false" title="Toggle Primary navigation">
			<span class="sr-only label">Toggle Primary Nav</span>
			<span class="fas fa-bars fa-lg fa-fw"></span>
		</button>
	
	</nav>
	<!-- /* End Right Navbar */ -->
	<?php
}