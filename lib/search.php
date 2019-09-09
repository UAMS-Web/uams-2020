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
            <button type="submit" id="searchsubmit" value="'. esc_attr__('Search', 'uams-2020') .'" class="btn btn-primary"><span class="fas fa-search"></span><span class="sr-only">Search</span></button>
        </div>
    </div>
    <div class="form-group text-center" role="radiogroup" aria-labelledby="uams-search-picker-label">
        <span class="sr-only" id="uams-search-picker-label">Choose which site to search.</span>
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="search-scope-1" name="search-scope" class="custom-control-input" checked>
            <label class="custom-control-label" for="search-scope-1">Current site</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="search-scope-2" name="search-scope" class="custom-control-input">
            <label class="custom-control-label" for="search-scope-2">All of UAMS</label>
        </div>
    </div>
    </form>';
    return $form;
}
