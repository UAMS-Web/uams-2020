<?php
/**
 * Quick Links
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

/* Removing Quick Links action via comments in case we decide to bring it back. */
// add_action( 'genesis_after', 'uamswp_quicklinks', 4 );

function uamswp_quicklinks() {

	?>
	<nav id="quick-links" class="closed" aria-label="Quick Links">
		<button type="button" id="toggle-quick-links-inside" aria-controls="quick-links" aria-expanded="false" aria-label="Toggle Quick Links navigation">
			<span class="sr-only label">Expand Quick Links</span>
			<span class="fas fa-times fa-lg fa-fw"></span>
		</button>
		<h2 class="">Quick Links</h2>
		<?php

		if ( site_custom_quicklinks() ) {

			if (
				( $locations = get_nav_menu_locations() )
				&&
				isset($locations[ 'quick-links' ])
			) {

				// Read quick links menu from this site
				
					$menu = wp_get_nav_menu_object( $locations[ 'quick-links' ] );

				$menu_items = wp_get_nav_menu_items($menu->term_id);

				$menu_list = '<ul class="list-unstyled links links-large" id="menu-quick-links">';

				foreach ( (array) $menu_items as $key => $menu_item ) {

					$title = $menu_item->title;
					$url = $menu_item->url;
					$classes = $menu_item->classes;
					$target = $menu_item->target;
					$target = !empty($target) ? ' target="'. $target .'"' : '';
					//$link_title = $menu_item->attr_title;
					$menu_list .= '<li><a href="' . esc_url( $url ) . '"'. $target .'>';
					$menu_list .= !empty($classes) ? '<span class="'. implode( " ", $classes ) .' fa-fw"></span>' : '';
					$menu_list .= $title . '</a></li>';

				}

				$menu_list .= '</ul>';

				echo $menu_list;

			} else {

				// Write Default

					?>
					<ul class="list-unstyled links links-large">
						<li><a href="https://gus.uams.edu/"><span class="fas fa-graduation-cap fa-fw "></span>GUS</a></li>
						<li><a href="https://uams-triprofiles.uams.edu/profiles/search/"><span class="fas fa-users fa-fw "></span>Profiles</a></li>
						<li><a href="https://library.uams.edu/"><span class="fas fa-book fa-fw "></span>Library</a></li>
						<li><a href="http://libguides.uams.edu/onlinebookstore"><span class="fas fa-shopping-cart fa-fw "></span>UAMS Bookstore</a></li>
					</ul>
					<?php

			}

		} else {

			uamswp_request_quicklinks();

		}

		?>
		<h3 class="h5">Campus Links</h3>
		<ul class="list-unstyled links">
			<li><a href="https://webmail.uams.edu/"><span class="far fa-envelope fa-fw "></span>Webmail</a></li>
			<li><a href="https://enterprise.uams.edu/irj/portal"><span class="far fa-id-card fa-fw "></span>Employee Self Service</a></li>
			<li><a href="https://www.uams.edu/IT/"><span class="fas fa-desktop fa-fw "></span>Computing / IT</a></li>
			<li><a href="https://inside.uams.edu/"><span class="fas fa-network-wired fa-fw "></span>Intranet</a></li>
		</ul>
	</nav>
	<?php

}

function uamswp_request_quicklinks() {

	$remote_url = 'http://acf.local/wp-json/menus/v2/quicklinks/';  // Base URL - Currently Dev URL

	if ( 'uamshealth' == uams_get_site_info()['site'] ) {

		$remote_url = 'http://acf.local/wp-json/menus/v2/quicklinks/'; // UAMS Health URL

	} elseif ( 'inside' == uams_get_site_info()['site'] ) {

		$remote_url = 'http://acf.local/wp-json/menus/v2/quicklinks/'; // Inside URL

	}

	$request = wp_remote_get( $remote_url );  // Dev URL

	if ( is_wp_error( $request ) ) {

		return false; // Bail early

	}

	$body = wp_remote_retrieve_body( $request );
	$data = json_decode( $body );

	if ( ! empty( $data ) ) {

		echo '<ul class="list-unstyled links links-large">';

		foreach( $data as $key => $menu_item ) {

			echo '<li>';
				echo '<a href="' . esc_url( $menu_item->url ) . '"'. ( !empty($menu_item->target) ? ' target="'. $menu_item->target .'"' : '' ) .'>'. ( !empty($menu_item->classes) ? '<span class="'. implode( " ", $menu_item->classes ) .' fa-fw"></span>' : '' ) . $menu_item->title . '</a>';
			echo '</li>';

		}

		echo '</ul>';

	}

}

// Quick Link Functions

	// Site gets custom quick links

		function site_custom_quicklinks() {

			if (
				( 'institute' == uams_get_site_info()['site'] )
				||
				(
					'uamshealth' == uams_get_site_info()['site']
					&&
					'main' == uams_get_site_info()['subsite']
				)
				||
				(
					'inside' == uams_get_site_info()['site']
					&&
					'main' == uams_get_site_info()['subsite']
				)
				||
				(
					'uams' == uams_get_site_info()['site']
					&&
					'main' == uams_get_site_info()['subsite']
				)
			) {

				return true;

			} else {

				return false;

			}

		}

	function register_quicklinks_menu() {

		if ( site_custom_quicklinks() ) {

			register_nav_menu( 'quick-links' ,__( 'Quick Links Menu' ));

		}

	}

	add_action( 'init', 'register_quicklinks_menu' );

	if ( site_custom_quicklinks() ) {

		// Add quick links menu
		
			// add_action( 'init', 'register_quicklinks_menu' );

		// Register function to run at rest_api_init hook

			add_action( 'rest_api_init', function () {

				// Setup siteurl/wp-json/menus/v2/header

					register_rest_route( 'menus/v2', '/quicklinks', array(
						'methods' => 'GET',
						'callback' => 'quicklinks_menu',
						'args' => array(
							'id' => array(
								'validate_callback' => function($param, $request, $key) {
									return is_numeric( $param );
								}
							),
						)
					));

			});

		// Callback function to generate quick links for REST API

			function quicklinks_menu( $data ) {

				// Verify that menu locations are available in your WordPress site

					if (
						( $locations = get_nav_menu_locations() )
						&&
						isset($locations[ 'quick-links' ])
					) {

						// Retrieve the menu in location quick-links
						
							$menu = wp_get_nav_menu_object($locations['quick-links']);

						// Create an empty array to store our JSON
						
							$menuItems = array();

						// If the menu isn't empty, start process of building an array, otherwise return a 404 error
						
							if ( !empty($menu) ) {

								// Assign array of navigation items to $menu_items variable

									$menu_items = wp_get_nav_menu_items($menu->term_id);

								// If $menu_items isn't empty...

									if ( $menu_items ) {

										// For each menu item, verify the menu item has no parent and then push the menu item to the $menuItems array

											foreach ( $menu_items as $key => $menu_item ) {

												if ( $menu_item->menu_item_parent == 0 ) {

													array_push(
															$menuItems, array(
																	'id' => $menu_item->ID,
																	'title' => $menu_item->title,
																	'url' => $menu_item->url,
																	'classes' => $menu_item->classes,
																	'target' => $menu_item->target,
																	'link_title' => $menu_item->attr_title,
															)
													);

												}

											}

									}

							}

					} else {

						return new WP_Error(
							'no_menus',
							'Could not find any menus' . $locations[ 'primary' ],
							array(
									'status' => 404
							)
						);

					}

				// Return array of list items with title and url properties
				
					return $menuItems;

			}

	}



