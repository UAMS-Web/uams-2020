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
        'instructions' => 'ex. ID=2 ex. ID=2  <code>(&lt;div class="lwcw" data-options="id=<b>2</b>&format=html"&gt;)</code>',
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
    array(
        'key' => 'field_livewhale_background_color'. $suffix,
        'label' => 'Background Color',
        'name' => 'livewhale_background_color',
        'type' => 'select',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'choices' => array(
            'bg-white' => 'White',
            'bg-gray' => 'Gray',
        ),
        'default_value' => array(
            0 => 'bg-white',
        ),
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'return_format' => 'value',
        'ajax' => 0,
        'placeholder' => '',
    ),
);