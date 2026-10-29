<?php

/**
 * Search results template.
 *
 * Uses SearchWP when active (multisite results, post type filter links).
 * Falls back to WordPress' native search on the current site otherwise.
 *
 * Author: Todd McKee
 */

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'uamswp_do_search_results' );

remove_action( 'genesis_after_header', 'page_options', 5 );

add_action( 'genesis_before_loop', 'uamswp_search_page_before_entry', 5 );
add_action( 'genesis_before_loop', 'uamswp_search_page_heading', 8 );
add_action( 'genesis_after_loop', 'uamswp_search_page_after_entry', 10 );

/**
 * Output the search form, filters, result count and results.
 *
 * SearchWP results (with 'fields' => 'default') are stdClass objects with
 * ->id and ->site; native results are WP_Post objects. Both are handled.
 *
 * @return void
 */
function uamswp_do_search_results() {
	global $post, $wp_query;

	$current_blog_id = get_current_blog_id();
	$search_term     = get_search_query( false );
	$has_term        = '' !== trim( $search_term );
	$found           = (int) $wp_query->found_posts;
	$show_google     = $has_term && uamswp_show_google_search( $found );

	// Open Layout
	echo '<div class="uams-module bg-auto">';
	echo '<div class="container-fluid">';
	echo '<div class="search-content row">';
	echo '<div class="col-12">';
	echo '<div class="inner-container content-width">';
	echo '<div class="pb-4">';
	get_search_form();
	if ( function_exists( 'uamswp_search_post_type_links' ) && class_exists( '\\SearchWP\\Engine' ) ) {
		echo uamswp_search_post_type_links();
	}
	echo '</div>';

	// Result count / empty search.
	// The count is an h2 so the h3 result titles sit under it (h1 > h2 > h3),
	// and the Google section below gets its own h2 at the same level.
	if ( ! $has_term ) {
		echo '<p class="search-count">Enter a search term above to search UAMS sites.</p>';
	} else {
		printf(
			'<h2 class="search-count h5">%s %s for <span class="search-count-term">%s</span></h2>',
			esc_html( $found ? number_format_i18n( $found ) : 'No' ),
			esc_html( _n( 'result', 'results', $found, 'uams-2020' ) ),
			esc_html( $search_term )
		);
	}

	// The Loop
	if ( $has_term && have_posts() ) :
		while ( have_posts() ) :
			the_post();

			$result_id   = isset( $post->id ) ? (int) $post->id : (int) $post->ID;
			$result_site = isset( $post->site ) ? (int) $post->site : $current_blog_id;

			// Switch to the result's site if needed.
			$switched_site = false;
			if ( $current_blog_id !== $result_site ) {
				switch_to_blog( $result_site );
				$switched_site = true;
			}

			$post = get_post( $result_id );

			if ( $post ) {
				uamswp_get_template_part( 'results', $post->post_type, [ 'post_id' => $post->ID, 'blog_id' => $result_site ], '', STYLESHEETPATH . '/templates/parts' );
			}

			if ( $switched_site ) {
				restore_current_blog();
			}
		endwhile;
		wp_reset_postdata();

	elseif ( $has_term ) :
		if ( $show_google ) {
			echo '<p>Sorry, no content matched your criteria. Try the <a href="#uams-google-search">search of all UAMS sites</a> below.</p>';
		} else {
			echo '<p>Sorry, no content matched your criteria.</p>';
		}
	endif;

	// Close Layout
	echo '</div>'; // .inner-container
	echo '</div>'; // .col-12
	echo '</div>'; // .search-content
	genesis_posts_nav();
	echo '</div>'; // .container-fluid
	echo '</div>'; // .uams-module

	// Google Custom Search across all UAMS sites (see UAMS Settings > Search).
	if ( $show_google ) {
		uamswp_google_search_block( $search_term );
	}
}

/**
 * Whether to show the Google search section, based on the site's
 * UAMS Settings > Search option.
 *
 * fallback   – only when SearchWP is not active (default)
 * no_results – when this site's search finds nothing, or SearchWP is not active
 * always     – always, below this site's results
 * never      – never
 *
 * @param int $found Number of results found.
 * @return bool
 */
function uamswp_show_google_search( $found ) {
	$mode = function_exists( 'get_field' ) ? get_field( 'uamswp_search_google_display', 'option' ) : '';
	$mode = $mode ? $mode : 'fallback';

	$searchwp_active = class_exists( '\\SearchWP\\Query' );

	switch ( $mode ) {
		case 'never':
			return false;
		case 'always':
			return true;
		case 'no_results':
			return ! $searchwp_active || 0 === (int) $found;
		case 'fallback':
		default:
			return ! $searchwp_active;
	}
}

/**
 * Google Custom Search across UAMS.edu and UAMShealth.com sites.
 *
 * A regular, always-visible section (no toggle or accordion) with its own
 * heading and landmark. A plain link covers no-JavaScript visitors.
 *
 * @param string $search_term Unescaped search term.
 * @return void
 */
function uamswp_google_search_block( $search_term = '' ) {
	$google_url = add_query_arg(
		'q',
		rawurlencode( $search_term . ' site:uams.edu OR site:uamshealth.com' ),
		'https://www.google.com/search'
	);
	?>
	<section id="uams-google-search" class="uams-module bg-auto" aria-labelledby="uams-google-search-heading">
		<div class="container-fluid">
			<div class="row">
				<div class="widget_search col-12">
					<h2 id="uams-google-search-heading" class="module-title"><span class="title">Search All UAMS Sites</span></h2>
					<p class="content note">Results from Google for UAMS websites on the UAMS.edu and UAMShealth.com&nbsp;domains.</p>
					<div class="inner-container content-width">
						<script async src="https://cse.google.com/cse.js?cx=014806496997774146681:pyl1jhwi-bo"></script>
						<div class="gcse-searchresults-only" data-queryParameterName="s"></div>
						<noscript>
							<p><a href="<?php echo esc_url( $google_url ); ?>">Search all UAMS sites on Google</a></p>
						</noscript>
					</div>
				</div>
			</div>
		</div>
	</section>
	<?php
}

function new_excerpt_more( $more ) {
	return '... <a class="more-link" href="' . esc_url( get_permalink() ) . '">Continue Reading</a>';
}

function uamswp_excerpt_length( $length ) {
	return 20; // pull first 20 words
}

function uamswp_search_page_before_entry() {
	echo '<article class="entry" itemtype="https://schema.org/CreativeWork">';
}

function uamswp_search_page_heading() {
	echo '<header class="entry-header"><h1 class="entry-title" itemprop="headline">Search results for: ' . esc_html( get_search_query( false ) ) . '</h1></header>';
}

function uamswp_search_page_after_entry() {
	echo '</article>';
}

//* Prefix search breadcrumb trail with the text 'Search results for'
function uamswp_prefix_search_breadcrumb( $args ) {
	$args['labels']['search'] = 'Search results for ';
	return $args;
}
add_filter( 'genesis_breadcrumb_args', 'uamswp_prefix_search_breadcrumb' );

add_filter( 'get_the_excerpt', 'strip_shortcodes', 20 );

// modify the Excerpt read more link.
add_filter( 'excerpt_more', 'new_excerpt_more' );

// modify the length of post excerpts.
add_filter( 'excerpt_length', 'uamswp_excerpt_length' );

genesis();
