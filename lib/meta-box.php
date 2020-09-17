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
		// Theme Styles
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
				'health' => 'UAMS Health',
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
		// Site Locations
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
				'regional-campus' => 'Regional Campus',
				
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
		// Regional Campus
		array(
			'key' => 'field_regional_campus',
			'label' => 'Regional Campus',
			'name' => 'uamswp_regional_campus',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_location',
						'operator' => '==',
						'value' => 'regional-campus',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'regional-campus-e' => 'Regional Campus - East',
				'regional-campus-ne' => 'Regional Campus - Northeast',
				'regional-campus-nw' => 'Regional Campus - Northwest',
				'regional-campus-nc' => 'Regional Campus - North Central',
				'regional-campus-s' => 'Regional Campus - South',
				'regional-campus-sw' => 'Regional Campus - Southwest',
				'regional-campus-sc' => 'Regional Campus - South Central',
				'regional-campus-w' => 'Regional Campus - West',
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
		// Institutes
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
		// Institute on Aging Departments
		array(
			'key' => 'field_institute_aging_dept',
			'label' => 'Institute on Aging Department',
			'name' => 'uamswp_institute_aging_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_aging',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Eye Institute Departments
		array(
			'key' => 'field_institute_eye_dept',
			'label' => 'Eye Institute Department',
			'name' => 'uamswp_institute_eye_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_eye',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Spine & Neurosciences Institute Departments
		array(
			'key' => 'field_institute_spine_dept',
			'label' => 'Spine & Neurosciences Institute Department',
			'name' => 'uamswp_institute_spine_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_spine',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Institute for Digital Health & Innovation Departments
		array(
			'key' => 'field_institute_digi-health_dept',
			'label' => 'Institute for Digital Health & Innovation Department',
			'name' => 'uamswp_institute_digi-health_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_digi-health',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Psychiatric Research Institute Departments
		array(
			'key' => 'field_institute_pri_dept',
			'label' => 'Psychiatric Research Institute Department',
			'name' => 'uamswp_institute_pri_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_pri',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Translational Research Institute Departments
		array(
			'key' => 'field_institute_tri_dept',
			'label' => 'Translational Research Institute Department',
			'name' => 'uamswp_institute_tri_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_tri',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Cancer Institute Departments
		array(
			'key' => 'field_institute_cancer_dept',
			'label' => 'Cancer Institute Department',
			'name' => 'uamswp_institute_cancer_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_institute',
						'operator' => '==',
						'value' => 'institute_cancer',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// UAMS.edu Main Campus Organizations
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
				'nursing' => 'College of Nursing',
				'pharmacy' => 'College of Pharmacy',
				'public-health' => 'College of Public Health',
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
				'other' => 'Other Site (Multisite)',
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
		// College of Health Professions Departments
		array(
			'key' => 'field_uams_cohp_dept',
			'label' => 'College of Health Professions Department',
			'name' => 'uamswp_uams_cohp_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'health-prof',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// College of Medicine Departments
		array(
			'key' => 'field_uams_com_dept',
			'label' => 'College of Medicine Department',
			'name' => 'uamswp_uams_com_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'medicine',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'emergency-medicine' => 'Emergency Medicine',
				'family-medicine' => 'Family Medicine',
				'orthopaedic-surgery' => 'Orthopaedics',
				'otolaryngology' => 'Otolaryngology (ENT)',
				'pathology' => 'Pathology',
				'pediatrics' => 'Pediatrics',
				'urology' => 'Urology',
				'dept' => 'Other Department or Organization Unit',
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
		// College of Nursing Departments
		array(
			'key' => 'field_uams_con_dept',
			'label' => 'College of Nursing Department',
			'name' => 'uamswp_uams_con_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'nursing',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// College of Pharmacy Departments
		array(
			'key' => 'field_uams_cop_dept',
			'label' => 'College of Pharmacy Department',
			'name' => 'uamswp_uams_cop_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'pharmacy',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'arpoison' => 'Arkansas Poison and Drug Information Center',
				'dept' => 'Other Department or Organization Unit',
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
		// College of Public Health Departments
		array(
			'key' => 'field_uams_coph_dept',
			'label' => 'College of Public Health Department',
			'name' => 'uamswp_uams_coph_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'public-health',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Graduate School Departments
		array(
			'key' => 'field_uams_grad-school_dept',
			'label' => 'Grad School Department',
			'name' => 'uamswp_uams_grad-school_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'grad-school',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// Other 
		array(
			'key' => 'field_uams_other_dept',
			'label' => 'Other (Multisite)',
			'name' => 'uamswp_uams_other_dept',
			'type' => 'select',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_uams_subsite',
						'operator' => '==',
						'value' => 'other',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'choices' => array(
				'main' => 'Main site',
				'dept' => 'Department or Organizational Unit',
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
		// UAMS Health Organizations
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
				'main' => 'Main UAMS Health site',
				'uams-aux' => 'UAMS Auxiliary',
				'dept' => 'Department or Organizational Unit',
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
		// Inside UAMS Organizations
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
				'dept' => 'Department or Organizational Unit',
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
		// Custom Addresses
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
			'collapsed' => 'field_address_title',
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
			'type' => (class_exists('po_acf_plugin_maskfield') ? 'maskfield' : 'text'), // Telephone input
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '66',
				'class' => '',
				'id' => '',
			),
			'input_mask' => '(999) 999-9999',
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
			'type' => (class_exists('po_acf_plugin_maskfield') ? 'maskfield' : 'text'), // Telephone input
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
			'input_mask' => '(999) 999-9999',
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


acf_add_local_field_group(array(
	'key' => 'group_uamswp_gtm',
	'title' => 'Tag Manager',
	'fields' => array(
		array(
			'key' => 'field_gtm_accordion',
			'label' => 'Google Tag Manager',
			'name' => '',
			'type' => 'accordion',
			'instructions' => 'This will override the default Tag Manager ID',
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
			'key' => 'field_google_tag_manager_id',
			'label' => 'GTM Container ID',
			'name' => 'google_tag_manager_id',
			'type' => 'text',
			'instructions' => 'Leave Blank for Default',
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
	'menu_order' => 20,
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
 * Add Page Header Options
 * 
 */
acf_add_local_field_group(array(
	'key' => 'group_page_header_options',
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
				'landingpage' => '<i class="dashicons dashicons-format-image"></i> Marketing Landing Page',
				'hero' => '<i class="dashicons dashicons-slides"></i> Hero',
			),
			'allow_null' => 0,
			'default_value' => 'none',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_page_cover_image',
			'label' => 'Graphic Title Cover Image',
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
			'label' => 'Graphic Title Lead Paragraph',
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
			'key' => 'field_page_landing_page_message',
			'label' => '',
			'name' => 'page_landing_page_message',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'landingpage',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'acfe_permissions' => '',
			'message' => '<h3>Stop</h3>
				<p>The Marketing Landing Page Title option should only be used for Marketing Landing Pages.</p>',
			'new_lines' => '',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_page_landing_page_cover_image',
			'label' => 'Marketing Landing Page Cover Image (Desktop)',
			'name' => 'page_landing_page_cover_image',
			'type' => 'image',
			'instructions' => 'Recommended dimensions: 5120x1600 or larger. Minimum dimensions: 1920x600. The image will be automatically cropped to a 16:5 aspect ratio.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'landingpage',
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
			'min_height' => 600,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_page_landing_page_cover_image_mobile',
			'label' => 'Marketing Landing Page Cover Image (Mobile)',
			'name' => 'page_landing_page_cover_image_mobile',
			'type' => 'image',
			'instructions' => 'This image is optional. Add an image here if you do not want the mobile image to be automatically cropped from the desktop image.<br />Recommended dimensions: 1984x1612 or larger. Minimum dimensions: 992x806. The image will be automatically cropped to a 16:13 aspect ratio.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'landingpage',
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
			'min_width' => 992,
			'min_height' => 806,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => '',
			'mime_types' => '',
		),
		array(
			'key' => 'field_page_landing_page_heading',
			'label' => 'Marketing Landing Page Title Heading',
			'name' => 'page_landing_page_heading',
			'type' => 'text',
			'instructions' => 'Override for page title for the purposes of this header. The normal page title will still be used for meta title, search results, etc. There is a 62 character limit. You can reuse your normal page title here if it fits in the character limit.',
			'required' => 1,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'landingpage',
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
			'maxlength' => 62,
			'rows' => '',
			'new_lines' => '',
		),
		array(
			'key' => 'field_page_landing_page_description',
			'label' => 'Marketing Landing Page Title Lead Paragraph',
			'name' => 'page_landing_page_description',
			'type' => 'textarea',
			'instructions' => 'Appears below the page title. Basic description of the page. There is a 117 character limit.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_page_title_options',
						'operator' => '==',
						'value' => 'landingpage',
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
			'maxlength' => 117,
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
				'value' => 'services',
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
 * Add Post Header Options
 * 
 */
acf_add_local_field_group(array(
	'key' => 'group_post_header_options',
	'title' => 'Header Options',
	'fields' => array(
		array(
			'key' => 'field_post_title_options',
			'label' => 'Title Options',
			'name' => 'post_title_options',
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
				'cover' => '<i class="dashicons dashicons-format-image"></i> Cover',
			),
			'allow_null' => 0,
			'default_value' => 'none',
			'layout' => 'horizontal',
			'return_format' => 'value',
		),
		array(
			'key' => 'field_5d8ce2200eaac',
			'label' => 'Coming Soon',
			'name' => '',
			'type' => 'message',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_post_title_options',
						'operator' => '==',
						'value' => 'cover',
					),
				),
			),
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'message' => 'Cover image coming soon. Will not display yet.',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
		),
		array(
			'key' => 'field_post_cover_image',
			'label' => 'Cover Image',
			'name' => 'post_cover_image',
			'type' => 'image',
			'instructions' => 'Recommended dimensions: 5120x1920 or larger. Minimum dimensions: 1920x720. The image will be automatically cropped to a 8:3 aspect ratio.',
			'required' => 0,
			'conditional_logic' => array(
				array(
					array(
						'field' => 'field_post_title_options',
						'operator' => '==',
						'value' => 'cover',
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
	),
	'location' => array(
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
		array(
			'key' => 'field_page_hide_from_menu',
			'label' => 'Hide Breadcrumbs',
			'name' => 'page_hide_from_menu',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'hide-label',
				'id' => '',
			),
			'message' => 'Hide from auto menus',
			'default_value' => 0,
			'ui' => 0,
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
		array(
			'key' => 'field_page_hide_child_menu',
			'label' => 'Hide Sub-page menu',
			'name' => 'page_hide_child_menu',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'hide-label',
				'id' => '',
			),
			'message' => 'Hide related page menu (children)',
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
 * Add UAMS Post Attributes
 * 
 */

acf_add_local_field_group(array(
	'key' => 'group_5d893f05c1c49',
	'title' => 'UAMS Post Options',
	'fields' => array(
		array(
			'key' => 'field_post_hide_author',
			'label' => 'Hide Author',
			'name' => 'post_hide_author',
			'type' => 'true_false',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => 'hide-label',
				'id' => '',
			),
			'acfe_permissions' => '',
			'message' => 'Hide Author information',
			'default_value' => 0,
			'ui' => 0,
			'acfe_validate' => '',
			'acfe_update' => '',
			'ui_on_text' => '',
			'ui_off_text' => '',
		),
	),
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'post',
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
	'acfe_display_title' => '',
	'acfe_permissions' => '',
	'acfe_note' => '',
	'acfe_meta' => '',
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
$link_list = include( get_stylesheet_directory() .'/acf_fields/link-list.php' );
$overlay = include( get_stylesheet_directory() .'/acf_fields/overlay.php' );
$post_tile = include( get_stylesheet_directory() .'/acf_fields/post-category-tile.php' );
$post_tiles = include( get_stylesheet_directory() .'/acf_fields/post-category-tiles.php' );
$side_by_side = include( get_stylesheet_directory() .'/acf_fields/image-side-by-side.php' );
$stacked = include( get_stylesheet_directory() .'/acf_fields/stacked.php' );
$news = include( get_stylesheet_directory() .'/acf_fields/news.php' );
$gallery = include( get_stylesheet_directory() .'/acf_fields/gallery.php' );
$content = include( get_stylesheet_directory() .'/acf_fields/content.php' );
$livewhale = include( get_stylesheet_directory() .'/acf_fields/livewhale.php' );

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

