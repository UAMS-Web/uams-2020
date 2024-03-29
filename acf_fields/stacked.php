<?php
/*
 *
 * Stacked Image & Text Block Fields
 * 
 */
return array(
    array(
        'key' => 'field_stacked_intro'. $suffix,
        'label' => '',
        'name' => 'stacked_intro',
        'type' => 'message',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'message' => '<h2>UAMS Stacked Image &amp; Text Block</h2>',
        'new_lines' => '',
        'esc_html' => 0,
    ),
    array(
        'key' => 'field_stacked_heading'. $suffix,
        'label' => 'Module Heading',
        'name' => 'stacked_heading',
        'type' => 'text',
        'instructions' => 'Even if this heading is hidden, it is necessary for accessibility.',
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
        'key' => 'field_stacked_hide_heading'. $suffix,
        'label' => 'Hide the Module Heading?',
        'name' => 'stacked_hide_heading',
        'type' => 'true_false',
        'instructions' => 'The heading is necessary for page hierarchy. But it can be hidden from all but screen readers and search engines. This is <strong>strongly</strong> not recommended in most cases, as the visible heading provides a jumping-in point for users as they scan your page.',
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
        'key' => 'field_stacked_description'. $suffix,
        'label' => 'Description',
        'name' => 'stacked_description',
        'type' => 'textarea',
        'instructions' => 'Optional description. There is a 290 character limit.',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'maxlength' => '290',
        'rows' => '',
        'new_lines' => '',
    ),
    array(
        'key' => 'field_stacked_background_color'. $suffix,
        'label' => 'Background Color',
        'name' => 'stacked_background_color',
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
            'bg-auto' => 'Auto',
            'bg-white' => 'White',
            'bg-gray' => 'Gray',
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
    array(
        'key' => 'field_stacked_section'. $suffix,
        'label' => 'Items',
        'name' => 'stacked_section',
        'type' => 'repeater',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'collapsed' => '',
        'min' => 2,
        'max' => '',
        'layout' => 'block',
        'button_label' => '',
        'sub_fields' => array(
            array(
                'key' => 'stacked_section_intro'. $suffix,
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
                'message' => '<h3>Item Options</h3>',
                'new_lines' => 'wpautop',
                'esc_html' => 0,
            ),
            array(
                'key' => 'field_stacked_section_heading'. $suffix,
                'label' => 'Heading',
                'name' => 'stacked_section_heading',
                'type' => 'text',
                'instructions' => '78 character limit.',
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
                'maxlength' => 78,
            ),
            array(
                'key' => 'field_stacked_section_body'. $suffix,
                'label' => 'Body',
                'name' => 'stacked_section_body',
                'type' => 'textarea',
                'instructions' => '179 character limit.',
                'required' => 1,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'default_value' => '',
                'placeholder' => '',
                'maxlength' => '179',
                'rows' => 3,
                'new_lines' => '',
            ),
            array(
                'key' => 'field_stacked_section_image'. $suffix,
                'label' => 'Image',
                'name' => 'stacked_section_image',
                'type' => 'image',
                'instructions' => 'Recommended image dimensions: 910x512. Minimum image dimensions: 455x256. The image will automatically be cropped to a 16:9 aspect ratio.',
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
                'min_width' => 455,
                'min_height' => 256,
                'min_size' => '',
                'max_width' => '',
                'max_height' => '',
                'max_size' => '',
                'mime_types' => '',
            ),
            array(
                'key' => 'field_stacked_section_alt_override'. $suffix,
                'label' => 'Image Alt Text Override',
                'name' => 'stacked_section_alt_override',
                'type' => 'text',
                'instructions' => 'Alt text (alternative text) refers to invisible description of images which are read aloud to blind users on a screen reader. If you want to define alt text that is different from the alt text defined for this image in the media library, you can do so here.',
                'required' => 0,
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
                'maxlength' => 125,
            ),
            array(
                'key' => 'field_stacked_section_button_text'. $suffix,
                'label' => 'Button Text',
                'name' => 'stacked_section_button_text',
                'type' => 'text',
                'instructions' => '',
                'required' => 0,
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
                'key' => 'field_stacked_section_button_url'. $suffix,
                'label' => 'Button URL',
                'name' => 'stacked_section_button_url',
                'type' => 'link',
                'instructions' => 'Include http:// or https://.',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_stacked_section_button_text'. $suffix,
                            'operator' => '!=empty',
                        ),
                    ),
                ),
                'wrapper' => array(
                    'width' => '',
                    'class' => '',
                    'id' => '',
                ),
                'return_format' => 'array',
            ),
            // array(
            //     'key' => 'field_stacked_section_button_target'. $suffix,
            //     'label' => 'Open in New Window?',
            //     'name' => 'stacked_section_button_target',
            //     'type' => 'true_false',
            //     'instructions' => '',
            //     'required' => 0,
            //     'conditional_logic' => 0,
            //     'wrapper' => array(
            //         'width' => '',
            //         'class' => '',
            //         'id' => '',
            //     ),
            //     'message' => '',
            //     'default_value' => 0,
            //     'ui' => 1,
            //     'ui_on_text' => '',
            //     'ui_off_text' => '',
            // ),
            array(
                'key' => 'field_stacked_section_button_description'. $suffix,
                'label' => 'Button Link Description',
                'name' => 'stacked_section_button_description',
                'type' => 'text',
                'instructions' => 'This is needed for accessibility. It helps differentiate between multiple links that use the same text like "Learn more". Describe the intent of the link, like "Learn more about the ABC Department".',
                'required' => 1,
                'conditional_logic' => array(
                    array(
                        array(
                            'field' => 'field_stacked_section_button_text'. $suffix,
                            'operator' => '!=empty',
                        ),
                    ),
                ),
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
        ),
    ),
    array(
        'key' => 'field_stacked_more'. $suffix,
        'label' => 'Include link to something?',
        'name' => 'stacked_more',
        'type' => 'true_false',
        'instructions' => '',
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
        'key' => 'field_stacked_more_text'. $suffix,
        'label' => 'Heading',
        'name' => 'stacked_more_text',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_stacked_more'. $suffix,
                    'operator' => '==',
                    'value' => '1',
                ),
            ),
        ),
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
        'maxlength' => '',
    ),
    array(
        'key' => 'field_stacked_more_button_text'. $suffix,
        'label' => 'Button Text',
        'name' => 'stacked_more_button_text',
        'type' => 'text',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_stacked_more'. $suffix,
                    'operator' => '==',
                    'value' => '1',
                ),
            ),
        ),
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
        'key' => 'field_stacked_more_button_url'. $suffix,
        'label' => 'Button URL',
        'name' => 'stacked_more_button_url',
        'type' => 'link',
        'instructions' => '',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_stacked_more'. $suffix,
                    'operator' => '==',
                    'value' => '1',
                ),
            ),
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'return_format' => 'array',
    ),
    array(
        'key' => 'field_stacked_more_button_description'. $suffix,
        'label' => 'Button Link Description',
        'name' => 'stacked_more_button_description',
        'type' => 'text',
        'instructions' => 'This is needed for accessibility. It helps differentiate between multiple links that use the same text like "Learn more". Describe the intent of the link, like "Learn more about the ABC Department".',
        'required' => 1,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_stacked_more'. $suffix,
                    'operator' => '==',
                    'value' => '1',
                ),
            ),
        ),
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
        'key' => 'field_action_bar_geo_valid'. $suffix,
        'label' => 'GeoTargetingWP Installed?',
        'name' => 'geo_valid',
        'type' => 'radio',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
            'width' => '',
            'class' => 'hidden',
            'id' => '',
        ),
        'acfe_permissions' => '',
        'choices' => array(
            'false' => 'False',
            'true' => 'True',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'default_value' => 'false',
        'layout' => 'horizontal',
        'return_format' => 'value',
    ),
    array(
        'key' => 'field_stacked_regions'. $suffix,
        'label' => '<i class="dashicons dashicons-location-alt"></i> Region Filter',
        'name' => '',
        'type' => 'accordion',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_stacked_geo_valid'. $suffix,
                    'operator' => '==',
                    'value' => 'true',
                ),
            ),
        ),
        'wrapper' => array(
            'width' => '',
            'class' => '',
            'id' => '',
        ),
        'open' => 0,
        'multi_expand' => 0,
        'endpoint' => 0,
    ),
    array(
		'key' => 'field_stacked_geo'. $suffix,
		'label' => 'Regions',
		'name' => 'stacked_geo',
		'type' => 'radio',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_stacked_geo_valid'. $suffix,
                    'operator' => '==',
                    'value' => 'true',
                ),
            ),
        ),
		'wrapper' => array(
			'width' => '',
			'class' => '',
			'id' => '',
		),
        'acfe_permissions' => '',
        'choices' => array(
            'include' => 'Include',
            'exclude' => 'Exclude',
        ),
        'allow_null' => 0,
        'other_choice' => 0,
        'default_value' => '',
        'layout' => 'horizontal',
        'return_format' => 'value'
    ),
    array(
		'key' => 'field_stacked_geo_region'. $suffix,
		'label' => 'Regions',
		'name' => 'stacked_geo_region',
		'type' => 'select',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => 0,
		'wrapper' => array(
			'width' => '',
			'class' => '',
			'id' => '',
		),
        'acfe_permissions' => '',
        'choices' => array(
            'central' => 'Central',
            'northeast' => 'Northeast',
            'northwest' => 'Northwest',
            'southeast' => 'Southeast',
            'southwest' => 'Southwest',
        ),
        'default_value' => array(
        ),
        'allow_null' => 0,
        'multiple' => 1,
        'ui' => 1,
        'ajax' => 0,
        'return_format' => 'value',
        'allow_custom' => 0,
        'placeholder' => '',
        'search_placeholder' => '',
    ),
);