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
		$header_image = '<picture>
		<source srcset="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark.svg" media="(min-width: 576px)">
		<source srcset="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_vertical_dark.svg" media="(min-width: 1px)">
		<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_health_horizontal_dark_386x50.png" alt="UAMS Health Logo" />
		</picture>';
		$header_image_link = 'https://uamshealth.com';
		$header_image_text = 'UAMS Health';
	}
	else {
		$header_image = '<picture>
		<source srcset="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_main_dark.svg" media="(min-width: 1px)">
		<img src="' . get_stylesheet_directory_uri() .'/assets/svg/uams-logo_main_dark_189x50.png" alt="University of Arkansas for Medical Sciences Logo" />
		</picture>';
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
		} elseif ( // If it's an institute, college or grad school
			'institute' == uams_get_site_info()['site']
			|| 'health-prof' == uams_get_site_info()['subsite']
			|| 'medicine' == uams_get_site_info()['subsite']
			|| 'nursing' == uams_get_site_info()['subsite']
			|| 'pharmacy' == uams_get_site_info()['subsite']
			|| 'public-health' == uams_get_site_info()['subsite']
			|| 'grad-school' == uams_get_site_info()['subsite']
			|| 'other' == uams_get_site_info()['subsite']
			) {
			switch_to_blog(1);
			$root_title_text = get_bloginfo( 'name' );
			restore_current_blog();
			$root_title_split = false;
			$root_title_link = esc_url( network_site_url() );
			$site_title = uams_site_title();
			$site_title_link = esc_url( home_url( '/' ) );
			$subsection_title = get_the_title(uamswp_nav_subsection());
			$subsection_title_link = get_the_permalink( uamswp_nav_subsection() );
			// Test if Root site title contains three spaces. Split title into vars if so.
			$split_str = '   ';
			if(strpos($root_title_text, $split_str) !== false){
				$root_title_split = true;
				$split_str_pos = strpos($site_title, $split_str);
				$split_a_end = $split_str_pos;
				$split_b_begin = $split_str_pos + 3;
				$root_title_split_descr = substr($site_title, 0, $split_a_end);
				$root_title_split_function = substr($site_title, $split_b_begin);
				$root_title_text = str_replace($split_str, ' ', $root_title_text);
			}
			// Test if current site title contains three spaces. Replace with one space if so.
			if(strpos($site_title, $split_str) !== false){
				$site_title = str_replace($split_str, ' ', $site_title);
			}
			// Begin structure
			if (uamswp_nav_subsection() && 'main' != uams_get_site_info()['department'] && 'none' != uams_get_site_info()['department']) {
				// Subsection of site that is department/organization
				echo '<a class="parent" href="'.$site_title_link.'">'.$site_title.'</a><span class="sr-only">: </span>';
				echo '<a class="title" href="'.$subsection_title_link.'">'.$subsection_title.'</a>';
			} elseif (uamswp_nav_subsection()) {
				// Subsection of site that is not department/organization (includes Main)
				echo '<a class="parent" href="'.$root_title_link.'">'.$root_title_text.'</a><span class="sr-only">: </span>';
				echo '<a class="title" href="'.$subsection_title_link.'">'.$subsection_title.'</a>';
			} elseif ('main' != uams_get_site_info()['department']) {
				// All sites but main, not subsection
				echo '<a class="parent" href="'.$root_title_link.'">'.$root_title_text.'</a><span class="sr-only">: </span>';
				echo '<a class="title" href="'.$site_title_link.'">'.$site_title.'</a>';
			} elseif ($root_title_split) {
				// Main site, not subsection, split title
				echo '<a class="title-split" href="'.$site_title_link.'"><span class="title-descriptor">'.$root_title_split_descr.'</span> <span class="title-function">'.$root_title_split_function.'</span></a>';
			} else {
				// Main site, not subsection, not split title
				echo '<a class="title" href="'.$site_title_link.'">'.$site_title.'</a>';
			}
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
					<a class="nav-link" href="https://uamshealth.com/">UAMS Health</a>
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
					<a class="nav-link" href="https://uamshealth.com/">UAMS Health</a>
				</li>
				<!-- End right nav -->
				<?php } ?>
			</ul>
		</div>
		<ul class="nav resource-nav" id="nav-resource">
			<?php if ('uamshealth' == uams_get_site_info()['site']) { ?>
			<!-- uamshealth only -->
			<li class="nav-item">
				<a class="nav-link emergency-link" href="https://uamshealth.com/location/uams-emergency-room/" aria-label="Emergency Room"><svg class="" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ambulance" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" data-fa-i2svg=""><path fill="currentColor" d="M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h16c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm144-248c0 4.4-3.6 8-8 8h-56v56c0 4.4-3.6 8-8 8h-48c-4.4 0-8-3.6-8-8v-56h-56c-4.4 0-8-3.6-8-8v-48c0-4.4 3.6-8 8-8h56v-56c0-4.4 3.6-8 8-8h48c4.4 0 8 3.6 8 8v56h56c4.4 0 8 3.6 8 8v48zm176 248c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"></path></svg><span class="sr-only">Emergency Room</span></a>
			</li>
			<?php } // endif ?>
			<li class="nav-item">
				<button class="search-toggler" type="button" id="toggle-search" aria-controls="header-search" aria-expanded="false" title="Toggle Search">
					<span class="sr-only label">Toggle Search</span>
					<svg class="fa-search fa-fw" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="search" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"></path></svg>
					<svg class="fa-times fa-fw" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="times" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 352 512" data-fa-i2svg=""><path fill="currentColor" d="M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"></path></svg>
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
			<svg class="" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="bars" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M16 132h416c8.837 0 16-7.163 16-16V76c0-8.837-7.163-16-16-16H16C7.163 60 0 67.163 0 76v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16zm0 160h416c8.837 0 16-7.163 16-16v-40c0-8.837-7.163-16-16-16H16c-8.837 0-16 7.163-16 16v40c0 8.837 7.163 16 16 16z"></path></svg>
		</button>

		<style>
			.emergency-link svg {
				display: inline-block;
				height: 1em;
				overflow: visible;
				font-size: 1.3333333333em;
				line-height: .75em;
				vertical-align: -.0667em;
			}
			.search-toggler svg {
				display: inline-block;
				height: 1em;
				overflow: visible;
				font-size: 1.3333333333em;
				line-height: .75em;
				vertical-align: -.0667em;
			}
			.mobile-menu-toggler svg {
				display: inline-block;
				/* font-size: inherit; */
				height: 1em;
				overflow: visible;
				/* vertical-align: -.125em; */
				font-size: 1.3333333333em;
				line-height: .75em;
				vertical-align: -.0667em;
			}
		</style>
	
	</nav>
	<!-- /* End Right Navbar */ -->
	<?php
}