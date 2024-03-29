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
        'message' => '<h2>UAMS Livewhale Calendar Block</h2>',
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
        'instructions' => 'ex. ID=2 <code>(&lt;div class="lwcw" data-options="id=<b>2</b>&format=html"&gt;)</code>',
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
        'key' => 'field_livewhale_geo_valid'. $suffix,
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
        'key' => 'field_livewhale_regions'. $suffix,
        'label' => '<i class="dashicons dashicons-location-alt"></i> Region Filter',
        'name' => '',
        'type' => 'accordion',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_livewhale_geo_valid'. $suffix,
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
		'key' => 'field_livewhale_geo'. $suffix,
		'label' => 'Regions',
		'name' => 'livewhale_geo',
		'type' => 'radio',
		'instructions' => '',
		'required' => 0,
		'conditional_logic' => array(
            array(
                array(
                    'field' => 'field_livewhale_geo_valid'. $suffix,
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
		'key' => 'field_livewhale_geo_region'. $suffix,
		'label' => 'Regions',
		'name' => 'livewhale_geo_region',
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