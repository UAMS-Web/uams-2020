<?php

declare(strict_types=1);
/**
 * Breadcrumb
 *
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 *
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Changing breadcrumbs to Bootstrap's format
// @link http://jhtechservices.com/2015/05/integrate-bootstraps-breadcrumbs-into-genesis-theme/
add_filter('genesis_breadcrumb_args', 'uamswp_breadcrumb_args');
/**
 * @param  mixed  $args
 * @return array<mixed>
 */
function uamswp_breadcrumb_args($args): array
{
    $args = uamswp_field_array($args);
    $labels = uamswp_field_array($args['labels'] ?? null);
    $args['sep'] = '     ';
    $args['prefix'] = sprintf('<ol %s>', genesis_attr('breadcrumb'));
    $args['suffix'] = '</ol>';
    $labels['prefix'] = '';
    $labels['author'] = '';
    $labels['category'] = ''; // Genesis 1.6 and later
    $labels['tag'] = '';
    $labels['date'] = '';
    $labels['search'] = '';
    $labels['tax'] = '';
    $labels['post_type'] = '';
    $labels['404'] = ''; // Genesis 1.5 and later
    $args['labels'] = $labels;

    return $args;
}

add_filter('genesis_build_crumbs', 'uamswp_build_crumbs', 10, 2);
/**
 * @param  mixed  $crumbs
 * @return array<mixed>
 */
function uamswp_build_crumbs($crumbs): array
{
    $crumbs = uamswp_field_array($crumbs);

    foreach ($crumbs as &$crumb) {
        $crumb = uamswp_field_text($crumb);
        $crumb = str_replace('     ', '</li><li class="breadcrumb-item active" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">', $crumb);
        $crumb = str_replace('<a ', '<a itemprop="item" ', $crumb);
        $class = strpos($crumb, '</a>') ? 'class="breadcrumb-item"' : 'class="breadcrumb-item active"';
        $crumb = '<li '.$class.' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'.$crumb.'</li>';

    }

    return $crumbs;
}
