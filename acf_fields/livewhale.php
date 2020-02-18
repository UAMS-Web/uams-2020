<?php
/*
 *
 * LiveWhale Calendar Block Fields 
 * 
 */
return array(
    array(
        'key' => 'field_livewhale_intro'. $suffix,
        'label' => '',
        'name' => '',
        'type' => 'message',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'message' => '<h2>Livewhale</h2>',
        'new_lines' => 'wpautop',
        'esc_html' => 0,
    ),
    array(
        'key' => 'field_livewhale_heading'. $suffix,
        'label' => 'Heading',
        'name' => 'livewhale_heading',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
    ),
    array(
        'key' => 'field_livewhale_id'. $suffix,
        'label' => 'ID Number of Widget',
        'name' => 'livewhale_id',
        'type' => 'number',
        'instructions' => 'ex. ID=2 ',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'acfe_permissions' => '',
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'min' => 1,
        'max' => '',
        'step' => 1,
    ),
);