<?php
/*
 *
 * Section Block Fields 
 * 
 */
return array(
    array(
        'key' => 'field_section_heading'. $suffix,
        'label' => 'Section Title',
        'name' => 'section_heading',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'acfe_permissions' => '',
        'default_value' => 'Section Title - Please Replace',
        'placeholder' => 'Section Title - Please Replace',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
    ),
    array(
        'key' => 'field_section_hide_heading'. $suffix,
        'label' => 'Hide Heading',
        'name' => 'section_hide_heading',
        'type' => 'true_false',
        'instructions' => 'The heading is necessary for page hierarchy. But it can be hidden from all but screen readers and search engines. This is <strong>strongly</strong> not recommended in most cases, as the visible heading provides a jumping-in point for users as they scan your page.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'acfe_permissions' => '',
        'message' => '',
        'default_value' => 0,
        'ui' => 1,
        'ui_on_text' => '',
        'ui_off_text' => '',
    ),
    array(
        'key' => 'field_section_background_color'. $suffix,
        'label' => 'Background Color',
        'name' => 'section_background_color',
        'type' => 'select',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'acfe_permissions' => '',
        'choices' => array(
            'bg-auto' => 'Auto',
            'bg-white' => 'White',
            'bg-gray' => 'Gray',
            'bg-red' => 'Red',
            'bg-black' => 'Black',
            'bg-blue' => 'Blue',
            'bg-green' => 'Green',
            'bg-teal' => 'Teal',
            'bg-eggplant' => 'Eggplant',
            'bg-orange' => 'Orange',
        ),
        'default_value' => array(
            0 => 'bg-auto',
        ),
        'allow_null' => 0,
        'multiple' => 0,
        'ui' => 0,
        'return_format' => 'value',
        'ajax' => 0,
        'placeholder' => '',
    ),
);