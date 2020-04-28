<?php
/**
 * Customizer
 *
 * @package      Bootstrap for Genesis
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2017, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

add_action( 'customize_register', function( $wp_customize ) {
    // Add Default Settings
    $wp_customize->add_setting( 'uams-2020', array(
        'capability' => 'edit_theme_options',
        'type' => 'theme_mod'
    ) );

    $wp_customize->remove_section( 'genesis_breadcrumbs' );

    // // Add Bootstrap Panel
    // $wp_customize->add_panel( 'bootstrap', array(
    //     'title' => __( 'Bootstrap for Genesis', 'uams-2020' ),
    //     'priority' => 100
    // ) );

    // // Add Navigation Section
    // $wp_customize->add_section( 'navigation', array(
    //     'title' => __( 'Navigation Settings', 'uams-2020' ),
    //     'priority' => 10,
    //     'panel' => 'bootstrap'
    // ) );

    // //* Add Navigation Controls
    // $wp_customize->add_setting( 'navposition', array(
    //     'default' => 'sticky-top'
    // ) );

    // $wp_customize->add_control( 'navposition', array(
    //     'type' => 'select',
    //     'priority' => 10,
    //     'label' => __( 'Navigation Position', 'uams-2020' ),
    //     'section' => 'navigation',
    //     'choices' => array(
    //         'sticky-top' => __( 'Sticky Top', 'uams-2020' ),
    //         'fixed-top' => __( 'Fixed Top', 'uams-2020' ),
    //         'fixed-bottom' => __( 'Fixed Bottom', 'uams-2020' ),
    //     )
    // ) );

    // $wp_customize->add_setting( 'navcontainer', array(
    //     'default' => 'lg'
    // ) );

    // // Navigation Container
    // $wp_customize->add_control( 'navcontainer', array(
    //     'type' => 'select',
    //     'priority' => 20,
    //     'label' => __( 'Navigation Container', 'uams-2020' ),
    //     'section' => 'navigation',
    //     'choices' => array(
    //         'sm' => __( 'Small', 'uams-2020' ),
    //         'md' => __( 'Medium', 'uams-2020' ),
    //         'lg' => __( 'Large', 'uams-2020' ),
    //         'xl' => __( 'Extra Large', 'uams-2020' )
    //     )
    // ) );

    // // Navigation Color
    // $wp_customize->add_setting( 'navcolor', array(
    //     'default' => 'dark'
    // ) );

    // $wp_customize->add_control( 'navcolor', array(
    //     'type' => 'select',
    //     'priority' => 30,
    //     'label' => __( 'Navigation Background', 'uams-2020' ),
    //     'section' => 'navigation',
    //     'choices' => array(
    //         'light' => __( 'Light', 'uams-2020' ),
    //         'dark' => __( 'Dark', 'uams-2020' ),
    //         'primary' => __( 'Primary', 'uams-2020' )
    //     )
    // ) );
    
    // // Navigation Extras
    // $wp_customize->add_setting( 'navextra', array(
    //     'default' => 'search'
    // ) );

    // $wp_customize->add_control( 'navextra', array(
    //     'type' => 'select',
    //     'priority' => 40,
    //     'label' => __( 'Navigation Extras', 'uams-2020' ),
    //     'section' => 'navigation',
    //     'choices' => array(
    //         '' => __( 'None', 'uams-2020' ),
    //         'date' => __( 'Date', 'uams-2020' ),
    //         'search' => __( 'Search Form', 'uams-2020' )
    //     )
    // ) );

    // // Footer Section
    // $wp_customize->add_section( 'footer', array(
    //     'title' => __( 'Footer Section', 'uams-2020' ),
    //     'priority' => 40,
    //     'panel' => 'bootstrap'
    // ) );

    // $wp_customize->add_setting( 'footerwidgetbg', array(
    //     'default' => 'dark'
    // ) );

    // $wp_customize->add_control( 'footerwidgetbg', array(
    //     'type' => 'select',
    //     'priority' => 30,
    //     'label' => __( 'Footer Widget Background', 'uams-2020' ),
    //     'section' => 'footer',
    //     'choices' => array(
    //         'light' => __( 'Light', 'uams-2020' ),
    //         'dark' => __( 'Dark', 'uams-2020' ),
    //         'primary' => __( 'Primary', 'uams-2020' )
    //     )
    // ) );

    // $wp_customize->add_setting( 'footerbg', array(
    //     'default' => 'dark'
    // ) );

    // $wp_customize->add_control( 'footerbg', array(
    //     'type' => 'select',
    //     'priority' => 30,
    //     'label' => __( 'Footer Background', 'uams-2020' ),
    //     'section' => 'footer',
    //     'choices' => array(
    //         'light' => __( 'Light', 'uams-2020' ),
    //         'dark' => __( 'Dark', 'uams-2020' ),
    //         'primary' => __( 'Primary', 'uams-2020' )
    //     )
    // ) );
} );