<?php
/**
 * Search Form
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_filter( 'get_search_form', 'uamswp_search_form' );
function uamswp_search_form( $form ) {
    $form = '<form class="uams-search" role="search" method="get" id="searchform" action="' . home_url('/') . '" >
    <div class="input-group">
        <input type="search" class="form-control" id="uams-search-bar" value="' . get_search_query() . '" placeholder="' . esc_attr__('Search', 'uams-2020') . '..." name="s" autocomplete="off" aria-label="Enter search text">
        <div class="input-group-append">
            <button type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'uams-2020') .'" class="btn btn-primary" title="Search"><span class="fas fa-search"></span><span class="sr-only">Search</span></button>
        </div>
    </div>';

    // Keep the post type filter when searching again from a filtered view.
    if ( ! empty( $_GET['type'] ) ) {
        $form .= '<input type="hidden" name="type" value="' . esc_attr( sanitize_key( wp_unslash( $_GET['type'] ) ) ) . '">';
    }

    $form .= '</form>';
    return $form;
}

// UAMS Search (uamswp-search plugin): this theme renders its own results page
// (search.php) and keeps its own search form; the plugin only answers the query
// and adds its hidden scope/type fields to the form.
add_filter( 'uams_search_use_template', '__return_false' );
add_filter( 'uams_search_replace_form', '__return_false' );

// Keep search results pages out of search engine indexes.
add_filter( 'wp_robots', 'uamswp_search_robots' );
function uamswp_search_robots( $robots ) {
    if ( is_search() ) {
        $robots['noindex']   = true;
        $robots['noarchive'] = true;
        $robots['follow']    = true;
    }
    return $robots;
}

// ---------- Search results markup ----------

/**
 * Breadcrumb labels for a search result, like "Cancer Institute > Patient Care > Clinics".
 * Call while switched to the result's site. Plain text, not links: the title is the link.
 *
 * @param WP_Post $post Result post.
 * @return string[]
 */
function uamswp_search_crumbs( $post ) {
    $crumbs = array();

    // Results from another sub-site start with that site's name.
    if ( is_multisite() && ms_is_switched() ) {
        $crumbs[] = get_bloginfo( 'name' );
    }

    $ancestors = function ( $id ) {
        $titles = array();
        foreach ( array_reverse( get_post_ancestors( $id ) ) as $ancestor ) {
            $titles[] = get_the_title( $ancestor );
        }
        return $titles;
    };

    switch ( $post->post_type ) {
        case 'page':
            $crumbs = array_merge( $crumbs, $ancestors( $post->ID ) );
            break;
        case 'post':
            $crumbs[]   = 'News';
            $categories = get_the_category( $post->ID );
            if ( $categories && 'uncategorized' !== $categories[0]->slug ) {
                $crumbs[] = $categories[0]->name;
            }
            break;
        case 'attachment':
            $crumbs[] = 'Documents';
            if ( $post->post_parent ) {
                $crumbs   = array_merge( $crumbs, $ancestors( $post->post_parent ) );
                $crumbs[] = get_the_title( $post->post_parent );
            }
            break;
        default:
            $object = get_post_type_object( $post->post_type );
            if ( $object ) {
                $crumbs[] = $object->labels->name;
                if ( $object->hierarchical ) {
                    $crumbs = array_merge( $crumbs, $ancestors( $post->ID ) );
                }
            }
    }

    if ( ! $crumbs ) {
        $crumbs[] = get_bloginfo( 'name' );
    }

    return array_values( array_filter( array_map( 'wp_strip_all_tags', $crumbs ), 'strlen' ) );
}

/**
 * Breadcrumb markup. The page styling shows it above the title; in the
 * reading order it follows the title, so screen readers reach the title first.
 *
 * @param string[] $crumbs Labels.
 * @return string
 */
function uamswp_search_crumbs_html( $crumbs ) {
    if ( ! $crumbs ) {
        return '';
    }
    $sep = '<span class="search-result-crumb-sep" aria-hidden="true"> &rsaquo; </span>';
    return '<p class="search-result-crumbs"><span class="sr-only">Found in: </span>' . implode( $sep, array_map( 'esc_html', $crumbs ) ) . '</p>';
}

/**
 * One search result.
 *
 * @param array $r {
 *     @type int    $post_id Result post ID (on the current, possibly switched, site).
 *     @type string $title   Title; may contain basic inline HTML such as &nbsp;.
 *     @type string $url     Link.
 *     @type string $excerpt Excerpt HTML: already escaped, may contain <mark>.
 *     @type string $meta    Optional plain text line, such as a date.
 * }
 */
function uamswp_search_render_result( $r ) {
    $post = get_post( $r['post_id'] );
    if ( ! $post ) {
        return;
    }

    $title = wp_kses( $r['title'], array() + array( 'span' => array() ) );
    if ( 'attachment' === $post->post_type ) {
        $file  = get_attached_file( $post->ID );
        $size  = ( $file && file_exists( $file ) ) ? size_format( filesize( $file ) ) : '';
        $title .= ' <span class="search-result-filetype">(PDF' . ( $size ? ', ' . esc_html( $size ) : '' ) . ')</span>';
    }

    // Image only when the result has one. Decorative: the title beside it names the result.
    $image = has_post_thumbnail( $post )
        ? get_the_post_thumbnail( $post, 'thumbnail', array( 'alt' => '', 'loading' => 'lazy', 'class' => 'search-result-img' ) )
        : '';
    ?>
    <article class="search-result<?php echo $image ? ' has-image' : ''; ?>">
        <div class="search-result-body">
            <h3 class="search-result-title"><a href="<?php echo esc_url( $r['url'] ); ?>"><?php echo $title; // Escaped above. ?></a></h3>
            <?php echo uamswp_search_crumbs_html( uamswp_search_crumbs( $post ) ); // Escaped in helper. ?>
            <?php if ( ! empty( $r['meta'] ) ) : ?>
                <p class="search-result-meta"><?php echo esc_html( $r['meta'] ); ?></p>
            <?php endif; ?>
            <?php if ( '' !== trim( wp_strip_all_tags( (string) $r['excerpt'] ) ) ) : ?>
                <p class="search-result-excerpt"><?php echo wp_kses( $r['excerpt'], array( 'mark' => array(), 'br' => array(), 'a' => array( 'href' => array() ) ) ); ?></p>
            <?php endif; ?>
        </div>
        <?php if ( $image ) : ?>
            <div class="search-result-image"><?php echo $image; // Core markup. ?></div>
        <?php endif; ?>
    </article>
    <?php
}

/**
 * Featured results box (from UAMS Search's keyword rules).
 * Always visible when present; no toggle or accordion.
 *
 * @param object[] $items Items from uams_search_featured().
 */
function uamswp_search_render_featured( $items ) {
    if ( ! $items ) {
        return;
    }
    $home_host = wp_parse_url( home_url(), PHP_URL_HOST );
    ?>
    <section class="search-featured" aria-labelledby="search-featured-heading">
        <h2 id="search-featured-heading" class="sr-only">Featured results</h2>
        <ul class="search-featured-list">
            <?php foreach ( $items as $item ) :
                $crumbs = array();
                if ( 'post' === $item->kind && $item->site ) {
                    $switched = is_multisite() && get_current_blog_id() !== (int) $item->site;
                    if ( $switched ) {
                        switch_to_blog( (int) $item->site );
                    }
                    $post = get_post( (int) $item->id );
                    if ( $post ) {
                        $crumbs = uamswp_search_crumbs( $post );
                    }
                    if ( $switched ) {
                        restore_current_blog();
                    }
                }
                $host     = wp_parse_url( $item->url, PHP_URL_HOST );
                $external = 'link' === $item->kind && $host && $host !== $home_host;
                ?>
                <li class="search-featured-item<?php echo $item->image ? ' has-image' : ''; ?>">
                    <div class="search-result-body">
                        <h3 class="search-result-title"><a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?><?php if ( $external ) : ?><span class="sr-only"> (another website)</span><?php endif; ?></a></h3>
                        <?php echo uamswp_search_crumbs_html( $crumbs ); // Escaped in helper. ?>
                        <?php if ( '' !== $item->description ) : ?>
                            <p class="search-result-excerpt"><?php echo esc_html( $item->description ); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php if ( $item->image ) : ?>
                        <div class="search-result-image"><img class="search-result-img" src="<?php echo esc_url( $item->image ); ?>" alt="<?php echo esc_attr( $item->image_alt ); ?>" loading="lazy"></div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php
}

/**
 * Numbered pagination for the main search query.
 */
function uamswp_search_pagination() {
    $links = paginate_links( array(
        'type'      => 'list',
        'mid_size'  => 1,
        'prev_text' => '<span aria-hidden="true">&lsaquo;</span><span class="sr-only">Previous page</span>',
        'next_text' => '<span class="sr-only">Next page</span><span aria-hidden="true">&rsaquo;</span>',
    ) );
    if ( $links ) {
        echo '<nav class="search-pagination" aria-label="Search result pages">' . $links . '</nav>'; // Core markup.
    }
}

// Search results styles.
add_action( 'wp_enqueue_scripts', 'uamswp_search_results_styles', 20 );
function uamswp_search_results_styles() {
    if ( ! is_search() ) {
        return;
    }
    wp_add_inline_style( 'app-css', '
        .search-count { margin: 1.5rem 0 1rem; font-size: .9375rem; }
        .search-content .uams-search-filters { display: flex; flex-wrap: wrap; gap: .5rem; list-style: none; padding: 0; margin: 1rem 0 0; }
        .search-content .uams-search-types .uams-search-filters a { display: inline-block; padding: .375rem .875rem; min-height: 24px; border: 1px solid #740024; border-radius: 2px; color: #740024; text-decoration: none; font-size: .875rem; }
        .search-content .uams-search-types .uams-search-filters a:hover { text-decoration: underline; }
        .search-content .uams-search-types .uams-search-filters a[aria-current="page"] { background: #740024; color: #fff; font-weight: 600; }
        .search-content .uams-search-scope .uams-search-filters { gap: 1rem; font-size: .875rem; }
        .search-content .uams-search-scope a[aria-current="page"] { font-weight: 700; text-decoration-thickness: 3px; }
        .search-content .uams-search-notice { margin: 0 0 1rem; }
        .search-featured-list { list-style: none; padding: 0; margin: 0 0 2rem; display: grid; gap: 1rem; }
        .search-featured-item { display: flex; gap: 1.5rem; padding: 1.25rem 1.5rem; background: #fff; border: 1px solid #e3e3e3; box-shadow: 0 2px 6px rgba(0, 0, 0, .12); }
        .search-result { display: flex; gap: 1.5rem; padding: 1.25rem 0; border-bottom: 1px solid #d6d6d6; }
        .search-result-body { flex: 1; min-width: 0; display: flex; flex-direction: column; }
        .search-result-crumbs { order: -1; margin: 0 0 .375rem; font-size: .8125rem; color: #595959; }
        .search-result-crumb-sep { color: #767676; }
        .search-result-title { font-size: 1.25rem; line-height: 1.35; margin: 0 0 .5rem; }
        .search-result-title a { text-decoration: underline; text-underline-offset: .15em; }
        .search-result-filetype { font-size: .875rem; font-weight: 400; }
        .search-result-meta { margin: 0 0 .25rem; font-size: .875rem; color: #595959; }
        .search-result-excerpt { margin: 0; line-height: 1.6; }
        .search-result-excerpt mark { background: #fff0a8; color: #151515; font-weight: 700; padding: 0 .08em; }
        .search-result-image { flex: 0 0 auto; }
        .search-result-img { width: 96px; height: 96px; object-fit: cover; border-radius: 4px; }
        .search-pagination ul.page-numbers { display: flex; flex-wrap: wrap; gap: .5rem; list-style: none; padding: 0; margin: 2rem 0 0; }
        .search-pagination .page-numbers a, .search-pagination .page-numbers span { display: inline-block; min-width: 2.25rem; min-height: 2.25rem; padding: .375rem .5rem; text-align: center; }
        .search-pagination .page-numbers.current { background: #740024; color: #fff; }
        @media (max-width: 575.98px) { .search-result-img { width: 64px; height: 64px; } .search-featured-item { padding: 1rem; } }
        @media (forced-colors: active) { .search-result-excerpt mark { outline: 1px solid Mark; } .search-featured-item { border-color: CanvasText; } }
    ' );
}

// UAMS Settings > Search: when to show the Google search of all UAMS sites.
add_action( 'acf/init', 'uamswp_search_settings_fields' );
function uamswp_search_settings_fields() {
    if ( ! function_exists( 'acf_add_local_field_group' ) ) {
        return;
    }

    acf_add_local_field_group( array(
        'key'    => 'group_uamswp_search_settings',
        'title'  => 'Search',
        'fields' => array(
            array(
                'key'           => 'field_uamswp_search_google_display',
                'label'         => 'Google search of all UAMS sites',
                'name'          => 'uamswp_search_google_display',
                'type'          => 'radio',
                'instructions'  => 'Shows a "Search All UAMS Sites" section with Google results for UAMS.edu and UAMShealth.com on the search results page.',
                'required'      => 0,
                'choices'       => array(
                    'fallback'   => 'Only when UAMS Search is not available (default)',
                    'no_results' => 'When this site\'s search finds no results',
                    'always'     => 'Always, below this site\'s results',
                    'never'      => 'Never',
                ),
                'default_value' => 'fallback',
                'layout'        => 'vertical',
                'return_format' => 'value',
                'allow_null'    => 0,
                'other_choice'  => 0,
                'save_other_choice' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'uamswp-settings',
                ),
            ),
        ),
        'menu_order'            => 50,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'active'                => true,
    ) );
}
