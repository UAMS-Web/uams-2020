<?php

/*
 *
 * ACF Meta Boxes
 * 
 */
// Remove ACF UI
add_filter('acf/settings/show_admin', 'my_acf_show_admin');

function my_acf_show_admin( $show ) {
    
    return current_user_can('manage_options');
    
}
// Create Options Page
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'UAMS Settings',
		'menu_title'	=> 'UAMS Settings',
		'menu_slug' 	=> 'uamswp-settings',
		'capability'	=> 'edit_posts',
        'redirect'		=> false,
        'autoload'      => true,
        'update_button'		=> __('Save Settings', 'acf'),
        'updated_message'	=> __("Settings Updated", 'acf'),
    ));

}
// Add metaboxes for Settings page
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array(
	'key' => 'group_uams_theme_settings',
	'title' => 'Theme Settings',
	'fields' => array(
		array(
			'key' => 'field_template',
			'label' => 'Theme Style',
			'name' => 'uamswp_template',
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
				'uams' => 'UAMS.edu',
				'health' => 'UAMSHealth',
				'inside' => 'Inside',
				'institute' => 'Institute',
			),
			'default_value' => array(
				0 => 'uams',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 1,
			'ajax' => 0,
			'return_format' => 'value',
			'placeholder' => '',
		),
		array(
			'key' => 'field_location',
			'label' => 'Site Location',
			'name' => 'uamswp_location',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'uams',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'uams' => 'Main Campus',
				'nw-campus' => 'Northwest Campus',
				'regional-campus-e' => 'Regional Campus - East',
				'regional-campus-ne' => 'Regional Campus - Northeast',
				'regional-campus-nw' => 'Regional Campus - Northwest',
				'regional-campus-nc' => 'Regional Campus - North Central',
				'regional-campus-s' => 'Regional Campus - South',
				'regional-campus-sw' => 'Regional Campus - Southwest',
				'regional-campus-sc' => 'Regional Campus - South Central',
				'regional-campus-w' => 'Regional Campus - West',
			),
			'default_value' => array(
				0 => 'uams',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_institute',
			'label' => 'UAMS Institute',
			'name' => 'uamswp_institute',
			'type' => 'select',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'institute',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'institute_aging' => 'Donald W. Reynolds Institute on Aging',
				'institute_eye' => 'Harvey & Bernice Jones Eye Institute',
				'institute_spine' => 'Jackson T. Stephens Spine & Neurosciences Institute',
				'institute_digi-health' => 'Institute for Digital Health & Innovation',
				'institute_pri' => 'Psychiatric Research Institute',
				'institute_tri' => 'Translational Research Institute',
				'institute_cancer' => 'Winthrop P. Rockefeller Cancer Institute',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_uams_subsite',
			'label' => 'Organization',
			'name' => 'uamswp_uams_subsite',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_location',
						'operator' => '==',
						'value' => 'uams',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'health-prof' => 'College of Health Professions',
				'medicine' => 'College of Medicine',
				'medicine_emergency-medicine' => 'College of Medicine -	Emergency Medicine',
				'medicine_pediatrics' => 'College of Medicine - Pediatrics',
				'medicine_otolaryngology' => 'College of Medicine - ENT',
				'medicine_family-medicine' => 'College of Medicine - Family Medicine',
				'medicine_orthopaedic-surgery' => 'College of Medicine - Orthopaedics',
				'medicine_pathology' => 'College of Medicine - Pathology',
				'medicine_urology' => 'College of Medicine - Urology',
				'nursing' => 'College of Nursing',
				'pharmacy' => 'College of Pharmacy',
				'pharmacy_arpoison' => 'College of Pharmacy - Arkansas Poison and Drug Information Center',
				'public-health' => 'College of Publice Health',
				'grad-school' => 'Graduate school',
				'12th-st' => '12th St. Health & Wellness Center',
				'cda' => 'Center for Diversity Affairs',
				'health-literacy' => 'Center for Health Literacy',
				'continuing-ed' => 'Continuing Education',
				'get-healthy' => 'Get Healthy UAMS',
				'gsa' => 'Graduate Student Association',
				'ipe' => 'Office of Interprofessional Education',
				'library' => 'Library',
				'employee_nurses' => 'Nurses (Employee)',
				'main' => 'Main UAMS site',
				'none' => 'None of the above',
			),
			'default_value' => array(
				0 => 'none',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 1,
			'ajax' => 1,
			'return_format' => 'value',
			'placeholder' => '',
		),
		array(
			'key' => 'field_uamshealth_subsite',
			'label' => 'Organization',
			'name' => 'uamswp_uamshealth_subsite',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'health',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'uams-aux' => 'UAMS Auxiliary',
				'none' => 'None of the above',
			),
			'default_value' => array(
				0 => 'none',
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_inside_subsite',
			'label' => 'Organization',
			'name' => 'uamswp_inside_subsite',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'inside',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'fitness-center' => 'Fitness Center',
				'main' => 'Main Site',
				'none' => 'None of the above',
			),
			'default_value' => array(
			),
			'allow_null' => 0,
			'multiple' => 0,
			'ui' => 0,
			'return_format' => 'value',
			'ajax' => 0,
			'placeholder' => '',
		),
		array(
			'key' => 'field_address_accordion',
			'label' => 'Custom Addresses',
			'name' => '',
			'type' => 'accordion',
			'instructions' => 'Custom address(s), if needed',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'institute',
					),
				),
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'uams',
					),
					array(
						'field' => 'field_location',
						'operator' => '!=',
						'value' => 'uams',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'open' => 1,
			'multi_expand' => 1,
			'endpoint' => 0,
		),
		array(
			'key' => 'field_address',
			'label' => 'Custom Address',
			'name' => 'uamswp_address',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'institute',
					),
				),
				array(
					array(
						'field' => 'field_template',
						'operator' => '==',
						'value' => 'uams',
					),
					array(
						'field' => 'field_location',
						'operator' => '!=',
						'value' => 'uams',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => 'field_5ced844860649',
			'min' => 1,
			'max' => 2,
			'layout' => 'block',
			'button_label' => '',
			'sub_fields' => array(
				array(
					'key' => 'field_address_title',
					'label' => 'Address Name',
					'name' => 'address_title',
					'type' => 'text',
					'instructions' => 'Leave blank for a single address',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'acfe_validate' => '',
					'acfe_update' => '',
							'default_value' => '',
					'placeholder' => 'Mailing Address',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_address_street_1',
					'label' => 'Address',
					'name' => 'address_street_1',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'acfe_validate' => '',
					'acfe_update' => '',
							'default_value' => '',
					'placeholder' => '4301 West Markham St #123',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_address_street_2',
					'label' => 'Address (2)',
					'name' => 'address_street_2',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'acfe_validate' => '',
					'acfe_update' => '',
							'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_address_city',
					'label' => 'City',
					'name' => 'address_city',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'acfe_validate' => '',
					'acfe_update' => '',
							'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
				array(
					'key' => 'field_address_state',
					'label' => 'State',
					'name' => 'address_state',
					'type' => 'select',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '30',
						'class' => '',
						'id' => '',
					),
					'acfe_validate' => '',
					'acfe_update' => '',
							'choices' => array(
						'AL' => 'Alabama',
						'AK' => 'Alaska',
						'AZ' => 'Arizona',
						'AR' => 'Arkansas',
						'CA' => 'California',
						'CO' => 'Colorado',
						'CT' => 'Connecticut',
						'DE' => 'Delaware',
						'DC' => 'District of Columbia',
						'FL' => 'Florida',
						'GA' => 'Georgia',
						'HI' => 'Hawaii',
						'ID' => 'Idaho',
						'IL' => 'Illinois',
						'IN' => 'Indiana',
						'IA' => 'Iowa',
						'KS' => 'Kansas',
						'KY' => 'Kentucky',
						'LA' => 'Louisiana',
						'ME' => 'Maine',
						'MD' => 'Maryland',
						'MA' => 'Massachusetts',
						'MI' => 'Michigan',
						'MN' => 'Minnesota',
						'MS' => 'Mississippi',
						'MO' => 'Missouri',
						'MT' => 'Montana',
						'NE' => 'Nebraska',
						'NV' => 'Nevada',
						'NH' => 'New Hampshire',
						'NJ' => 'New Jersey',
						'NM' => 'New Mexico',
						'NY' => 'New York',
						'NC' => 'North Carolina',
						'ND' => 'North Dakota',
						'OH' => 'Ohio',
						'OK' => 'Oklahoma',
						'OR' => 'Oregon',
						'PA' => 'Pennsylvania',
						'RI' => 'Rhode Island',
						'SC' => 'South Carolina',
						'SD' => 'South Dakota',
						'TN' => 'Tennessee',
						'TX' => 'Texas',
						'UT' => 'Utah',
						'VT' => 'Vermont',
						'VA' => 'Virginia',
						'WA' => 'Washington',
						'WV' => 'West Virginia',
						'WI' => 'Wisconsin',
						'WY' => 'Wyoming',
					),
					'default_value' => array(
						0 => 'AR',
					),
					'allow_null' => 0,
					'multiple' => 0,
					'ui' => 0,
					'return_format' => 'value',
					'ajax' => 0,
					'placeholder' => '',
				),
				array(
					'key' => 'field_address_zip',
					'label' => 'Zip',
					'name' => 'address_zip',
					'type' => 'text',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '20',
						'class' => '',
						'id' => '',
					),
					'acfe_validate' => '',
					'acfe_update' => '',
							'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'maxlength' => '',
				),
			),
        ),
		array(
			'key' => 'field_submit_button',
			'label' => '',
			'name' => '',
			'type' => 'button',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'button_value' => 'Save',
			'button_attributes' => array(
				'button_type' => 'submit',
				'button_class' => 'button button-primary button-large',
				'button_id' => '',
			),
			'button_wrapper' => array(
				'button_before' => '',
				'button_after' => '',
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'uamswp-settings',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'active' => true,
));

acf_add_local_field_group(array(
	'key' => 'group_uamswp_phone',
	'title' => 'Phone',
	'fields' => array(
		array(
			'key' => 'field_phone_accordion',
			'label' => 'Phone Number',
			'name' => '',
			'type' => 'accordion',
			'instructions' => 'This will override the default phone number',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'open' => 1,
			'multi_expand' => 1,
			'endpoint' => 0,
		),
		array(
			'key' => 'field_primary_phone_text',
			'label' => 'Phone Text',
			'name' => 'uamswp_primary_phone_text',
			'type' => 'text',
			'instructions' => '',
			'required' => 1,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '33',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Phone',
			'placeholder' => 'Phone',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_primary_phone_number',
			'label' => 'Phone Number',
			'name' => 'uamswp_primary_phone_number',
			'type' => (class_exists('jony_acf_plugin_intl_tel_input') ? 'intl_tel_input' : 'text'), // Telephone input
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '66',
				'class' => '',
				'id' => '',
			),
			'separateDialCode' => 0,
			'allowDropdown' => 0,
			'initialCountry' => 'us',
			'excludeCountries' => '',
			'onlyCountries' => '',
			'preferredCountries' => '',
		),
		array(
			'key' => 'field_secondary_type',
			'label' => 'Secondary Number Type',
			'name' => 'uamswp_secondary_number_type',
			'type' => 'button_group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'none' => '<i class="dashicons dashicons-dismiss"></i> None',
				'phone' => '<i class="dashicons dashicons-phone"></i> Phone',
				'link' => '<i class="dashicons dashicons-admin-links"></i> Link',
			),
			'allow_null' => 0,
			'default_value' => 'none',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_secondary_link_text',
			'label' => 'Secondary Link Text',
			'name' => 'uamswp_secondary_link_text',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_secondary_type',
						'operator' => '==',
						'value' => 'link',
					),
				),
			),
			'wrapper' => array(
				'width' => '33',
				'class' => '',
				'id' => '',
			),
			'default_value' => 'Additional Phone Numbers',
			'placeholder' => 'Additional Phone Numbers',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_secondary_link_url',
			'label' => 'Additional Link',
			'name' => 'uamswp_secondary_link_url',
			'type' => 'link',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_secondary_type',
						'operator' => '==',
						'value' => 'link',
					),
				),
			),
			'wrapper' => array(
				'width' => '66',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'array',
		),
		array(
			'key' => 'field_secondary_phone_text',
			'label' => 'Secondary Phone Text',
			'name' => 'uamswp_secondary_phone_text',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_secondary_type',
						'operator' => '==',
						'value' => 'phone',
					),
				),
			),
			'wrapper' => array(
				'width' => '33',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => 'ex. Appointments',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
		),
		array(
			'key' => 'field_secondary_phone_number',
			'label' => 'Secondary Phone Number',
			'name' => 'uamswp_secondary_phone_number',
			'type' => (class_exists('jony_acf_plugin_intl_tel_input') ? 'intl_tel_input' : 'text'), // Telephone input
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_secondary_type',
						'operator' => '==',
						'value' => 'phone',
					),
				),
			),
			'wrapper' => array(
				'width' => '66',
				'class' => '',
				'id' => '',
			),
			'separateDialCode' => 0,
			'allowDropdown' => 0,
			'initialCountry' => 'us',
			'excludeCountries' => '',
			'onlyCountries' => '',
			'preferredCountries' => '',
		),
		array(
			'key' => 'field_phone_accordion_end',
			'label' => 'Phone End',
			'name' => '',
			'type' => 'accordion',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'open' => 0,
			'multi_expand' => 0,
			'endpoint' => 1,
		),
		array(
			'key' => 'field_submit_button_phone',
			'label' => '',
			'name' => '',
			'type' => 'button',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'button_value' => 'Save',
			'button_attributes' => array(
				'button_type' => 'submit',
				'button_class' => 'button button-primary button-large',
				'button_id' => '',
			),
			'button_wrapper' => array(
				'button_before' => '',
				'button_after' => '',
			),
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'options_page',
				'operator' => '==',
				'value' => 'uamswp-settings',
			),
		),
	),
	'menu_order' => 10,
	'position' => 'normal',
	'style' => 'seamless',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));


$suffix = '_o'; // Page Options
$hero = include( get_stylesheet_directory() .'/acf_fields/hero.php' );
/*
 *
 * Add Header Options
 * 
 */
acf_add_local_field_group(array(
	'key' => 'group_header_options',
	'title' => 'Header Options',
	'fields' => array(
		array(
			'key' => 'field_page_title_options',
			'label' => 'Title Options',
			'name' => 'page_title_options',
			'type' => 'button_group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'none' => '<i class="dashicons dashicons-yes"></i> Normal',
				'hidden' => '<i class="dashicons dashicons-no"></i> Hidden',
				'graphic' => '<i class="dashicons dashicons-format-image"></i> Graphic',
				'hero' => '<i class="dashicons dashicons-slides"></i> Hero',
			),
			'allow_null' => 0,
			'default_value' => 'none',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_page_cover_image',
			'label' => 'Cover Image',
			'name' => 'page_cover_image',
			'type' => 'image',
			'instructions' => 'Recommended dimensions: 5120x1920 or larger. Minimum dimensions: 1920x720. The image will be automatically cropped to a 8:3 aspect ratio.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'graphic',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'return_format' => 'id',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => 1920,
			'min_height' => 720,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_page_description',
			'label' => 'Lead Paragraph',
			'name' => 'page_description',
			'type' => 'textarea',
			'instructions' => 'Appears below the page title. Basic description of the page. 500 character limit.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'graphic',
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
			'maxlength' => 500,
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_page_hero',
			'label' => 'Hero',
			'name' => 'page_hero',
			'type' => 'group',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'hero',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'layout' => 'block',
			'sub_fields' => $hero,
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

/*
 *
 * Add UAMS Page Attributes
 * 
 */

acf_add_local_field_group(array(
	'key' => 'group_page_attributes',
	'title' => 'UAMS Page Attributes',
	'fields' => array(
		array(
			'key' => 'field_page_subsection',
			'label' => 'Page is a subsection',
			'name' => 'page_subsection',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'hide-label',
				'id' => '',
			),
			'message' => 'Page is a subsection',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_page_hide_breadcrumbs',
			'label' => 'Hide Breadcrumbs',
			'name' => 'page_hide_breadcrumbs',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'hide-label',
				'id' => '',
			),
			'message' => 'Hide Breadcrumbs',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'side',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
));

/*
 *
 * Add ACF Modules
 * 
 */
$suffix = "_m";
$action_bar = include( get_stylesheet_directory() .'/acf_fields/action-bar.php' );
$call_out = include( get_stylesheet_directory() .'/acf_fields/call-out.php' );
$cta = include( get_stylesheet_directory() .'/acf_fields/cta.php' );
$hero = include( get_stylesheet_directory() .'/acf_fields/hero.php' );
$overlay = include( get_stylesheet_directory() .'/acf_fields/overlay.php' );
$post_tile = include( get_stylesheet_directory() .'/acf_fields/post-category-tile.php' );
$post_tiles = include( get_stylesheet_directory() .'/acf_fields/post-category-tiles.php' );
$side_by_side = include( get_stylesheet_directory() .'/acf_fields/image-side-by-side.php' );
$stacked = include( get_stylesheet_directory() .'/acf_fields/stacked.php' );
$news = include( get_stylesheet_directory() .'/acf_fields/news.php' );

$modules = require( get_stylesheet_directory() .'/acf_fields/modules.php' );
acf_add_local_field_group(array(
	'key' => 'group_5d1263f48f6fb',
	'title' => 'UAMS Modules',
	'fields' => $modules,
	'location' => array(
		array(
			array(
				'param' => 'page_template',
				'operator' => '==',
				'value' => 'templates/modules.php',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'left',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => true,
	'description' => '',
	'acfe_display_title' => '',
	'acfe_autosync' => '',
	'acfe_permissions' => '',
	'acfe_note' => '',
	'acfcdt_manage_table_definition' => 0,
	'acfcdt_table_definition_file_name' => 'table_613x5d1263f5763bb',
	'acfe_meta' => '',
));

endif;

