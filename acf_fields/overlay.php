<?php
/*
 *
 * Text & Image Overlay ACF Fields
 * 
 */
return array(
    array(
        'key' => 'field_overlay_intro'. $suffix,
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
        'message' => '<h2>Text-Image Overlay</h2>',
        'new_lines' => 'wpautop',
        'esc_html' => 0,
    ),
    array(
        'key' => 'field_overlay_section'. $suffix,
        'label' => 'Sections',
        'name' => 'overlay_section',
        'type' => 'repeater',
        'instructions' => 'You may have either one or two sections. If you only have one section, it will display full-width. Two sections will be displayed beside each other, half the width of the viewport.',
        'required' => 1,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => 'field_overlay_section_heading'. $suffix,
        'min' => 1,
        'max' => 2,
        'layout' => 'block',
        'button_label' => 'Add Section',
        'sub_fields' => array(
            array(
                'key' => 'field_overlay_section_heading'. $suffix,
                'label' => 'Heading',
                'name' => 'overlay_section_heading',
                'type' => 'text',
                'instructions' => '32 character limit.',
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
                'maxlength' => 32,
            ),
            array(
                'key' => 'field_overlay_section_body'. $suffix,
                'label' => 'Body',
                'name' => 'overlay_section_body',
                'type' => 'textarea',
                'instructions' => '280 character limit.',
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
                'maxlength' => 280,
            ),
            array(
                'key' => 'field_overlay_section_button_text'. $suffix,
                'label' => 'Button Text',
                'name' => 'overlay_section_button_text',
                'type' => 'text',
                'instructions' => '27 character limit.',
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
                'maxlength' => 26,
            ),
            array(
                'key' => 'field_overlay_section_button_url'. $suffix,
                'label' => 'Button URL',
                'name' => 'overlay_section_button_url',
                'type' => 'url',
                'instructions' => 'Include http:// or https://.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
            ),
            array(
                'key' => 'field_overlay_section_button_target'. $suffix,
                'label' => 'Open in New Window?',
                'name' => 'overlay_section_button_target',
                'type' => 'true_false',
                'instructions' => '',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'message' => '',
                'default_value' => 0,
                'ui' => 1,
                'ui_on_text' => '',
                'ui_off_text' => '',
            ),
            array(
                'key' => 'field_overlay_section_button_description'. $suffix,
                'label' => 'Button Link Description',
                'name' => 'overlay_section_button_description',
                'type' => 'text',
                'instructions' => 'This is needed for accessibility. It helps differentiate between multiple links that use the same text like "Learn more". Describe the intent of the link, like "Learn more about the ABC Department".',
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
                'key' => 'field_overlay_section_background_color'. $suffix,
                'label' => 'Background Color',
                'name' => 'overlay_section_background_color',
                'type' => 'select',
                'instructions' => 'Each section should use a different color. If this module is adjacent to another module with a background color, the adjacent modules/sections should all use different colors. If this module is adjacent to the footer, the color red should not be used, as the footer is red.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'choices' => array(
                    'bg-red' => 'Red',
                    'bg-black' => 'Black',
                    'bg-blue' => 'Blue',
                    'bg-green' => 'Green',
                    'bg-teal' => 'Teal',
                    'bg-eggplant' => 'Eggplant',
                    'bg-orange' => 'Orange',
                ),
                'default_value' => array(
                    0 => 'bg-black',
                ),
                'allow_null' => 0,
                'multiple' => 0,
                'ui' => 0,
                'return_format' => 'value',
                'ajax' => 0,
                'placeholder' => '',
            ),
            array(
                'key' => 'field_overlay_section_image'. $suffix,
                'label' => 'Image',
                'name' => 'overlay_section_image',
                'type' => 'image',
                'instructions' => 'Recommended image dimensions for single section: 5120x2560. Recommended image dimensions for two sections: 2560x1920. Minimum image dimensions: 1920x720. If there are two sections, the image will be automatically cropped to a 4:3 aspect ratio. If there is one section, the image will be automatically cropped to a 8:3 aspect ratio.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'id',
                'preview_size' => 'thumbnail',
                'library' => 'all',
                'min_width' => 1920,
                'min_height' => 720,
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
        ),
    ),
);