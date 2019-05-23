<?php

/*
 *
 * Custom Meta box
 * 
 */

// Subsection Attribute
add_filter( 'rwmb_meta_boxes', 'uamswp_register_meta_boxes' );
function uamswp_register_meta_boxes( $meta_boxes ) {
	$meta_boxes[] = array (
		'title' => 'Page Settings',
		'id' => 'page-settings',
		'post_types' => array(
			'page',
		),
		'context' => 'side',
		'priority' => 'high',
		'status' => 'publish',
		'fields' => array(
			
			array (
				'id' => 'page_subsection',
				'name' => 'Subsection',
				'type' => 'checkbox',
				'desc' => 'Page is a subsection',
			),
		),
	);
	return $meta_boxes;
}

// Register settings page. In this case, it's a theme options page
add_filter( 'mb_settings_pages', 'uamswp_options_page' );
function uamswp_options_page( $settings_pages ) {
    $settings_pages[] = array(
        'id'          => 'uamswp_options',
        'option_name' => 'uamswp_options',
        'menu_title'  => 'UAMS Settings',
        'icon_url'    => 'dashicons-admin-generic',
    );
    return $settings_pages;
}

// Register meta boxes and fields for settings page
add_filter( 'rwmb_meta_boxes', 'uamswp_options_meta_boxes' );
function uamswp_options_meta_boxes( $meta_boxes ) {
    $meta_boxes[] = array(
        'id'             => 'uamswp_settings',
        'title'          => 'Settings',
        'settings_pages' => 'uamswp_options',
        // 'tab'            => 'faq',
        'fields'         => array( 
            array(
                'id'             => 'uamswp_template',
                'name'          => 'Theme Style',
                'type'            => 'select',
                'placeholder'     => 'Select a site',
                // Array of 'value' => 'Label' pairs
                'options'         => array(
                    'uams'       => 'UAMS.edu',
                    'health'     => 'UAMSHealth',
                    'inside'     => 'Inside',
                    'institute'  => 'Institute',
                    //'other'      => 'Other',
                ),
            ),

            array(
                'id'             => 'uamswp_location',
                'name'           => 'Site Location',
                'type'            => 'select',
                // 'placeholder'     => 'Select a location',
                // Array of 'value' => 'Label' pairs
                'options'         => array(
                    'uams'                  => 'Main Campus',
                    'nw-campus'             => 'Northwest Campus',
                    'regional-campus-e'     => 'Regional Campus - East',
                    'regional-campus-ne'    => 'Regional Campus - Northeast',
                    'regional-campus-nw'    => 'Regional Campus - Northwest',
                    'regional-campus-nc'    => 'Regional Campus - North Central',
                    'regional-campus-s'     => 'Regional Campus - South',
                    'regional-campus-sw'    => 'Regional Campus - Southwest',
                    'regional-campus-sc'    => 'Regional Campus - South Central',
                    'regional-campus-w'     => 'Regional Campus - West',
                ),
                'std'       => 'uams',
                'hidden' => array( 'uamswp_template', '!=', 'uams' ),
            ),

            array(
                'id'             => 'uamswp_institute',
                'name'           => 'UAMS Institute',
                'type'            => 'select',
                'placeholder'     => 'Select an Institute',
                // Array of 'value' => 'Label' pairs
                'options'         => array(
                    'institute_aging'       => 'Donald W. Reynolds Institute on Aging',
                    'institute_eye'         => 'Harvey & Bernice Jones Eye Institute',
                    'institute_spine'       => 'Jackson T. Stephens Spine & Neurosciences Institute',
                    'institute_digi-health' => 'Institute for Digital Health & Innovation',
                    'institute_pri'         => 'Psychiatric Research Institute',
                    'institute_tri'         => 'Translational Research Institute',
                    'institute_cancer'      => 'Winthrop P. Rockefeller Cancer Institute',
                ),
                'hidden' => array( 'uamswp_template', '!=', 'institute' ),
            ),

            array(
                'id'             => 'uamswp_uams_subsite',
                'name'           => 'Organization',
                'type'           => 'select_advanced',
                'placeholder'     => 'Select an organization',
                // Array of 'value' => 'Label' pairs
                'options'         => array(
                    'health-prof'           => 'College of Health Professions',
                    'medicine'              => 'College of Medicine',
                    'medicine_emergency-medicine'   => 'College of Medicine -  Emergency Medicine',
                    'medicine_pediatrics'           => 'College of Medicine - Pediatrics',
                    'medicine_otolaryngology'       => 'College of Medicine - ENT',
                    'medicine_family-medicine'      => 'College of Medicine - Family Medicine',
                    'medicine_orthopaedic-surgery'  => 'College of Medicine - Orthopaedics',
                    'medicine_pathology'            => 'College of Medicine - Pathology',
                    'medicine_urology'              => 'College of Medicine - Urology',
                    'nursing'               => 'College of Nursing',
                    'pharmacy'              => 'College of Pharmacy',
                    'pharmacy_arpoison'     => 'College of Pharmacy - Arkansas Poison and Drug Information Center',
                    'public-health'         => 'College of Publice Health',
                    'grad-school'           => 'Graduate school',
                    '12th-st'               => '12th St. Health & Wellness Center',
                    'cda'                   => 'Center for Diversity Affairs',
                    'health-literacy'       => 'Center for Health Literacy',
                    'continuing-ed'         => 'Continuing Education',
                    'get-healthy'           => 'Get Healthy UAMS',
                    'gsa'                   => 'Graduate Student Association',
                    'ipe'                   => 'Office of Interprofessional Education',
                    'library'               => 'Library',
                    'employee_nurses'       => 'Nurses (Employee)',
                    'main'                  => 'Main UAMS site',
                    'none'                  => 'None of the above',
                ),
                'js_options'      => array(
                    'width' => '90%',
                ),
                'visible' => array( 'uamswp_location', '=', 'uams' ),
            ),

            array(
                'id'             => 'uamswp_uamshealth_subsite',
                'name'           => 'Organization',
                'type'            => 'select_advanced',
                'placeholder'     => 'Select an organization',
                // Array of 'value' => 'Label' pairs
                'options'         => array(
                    'uams-aux'           => 'UAMS Auxiliary',
                    'none'               => 'None of the above',
                ),
                'visible' => array( 'uamswp_template', '=', 'health' ),
            ),

            array(
                'id'             => 'uamswp_inside_subsite',
                'name'           => 'Organization',
                'type'            => 'select_advanced',
                'placeholder'     => 'Select an organization',
                // Array of 'value' => 'Label' pairs
                'options'         => array(
                    'fitness-center'     => 'Fitness Center',
                    'none'               => 'None of the above',
                ),
                'visible' => array( 'uamswp_template', '=', 'inside' ),
            ),

            array(
                'type' => 'heading',
                'name' => 'Custom Address',
                'desc' => 'Custom address(s), if needed',
                'hidden' => array( 
                    array('uamswp_template', '!=', 'institute'),
                    array('uamswp_location', '=', 'uams'),
                ),
            ),

            array(
                'id'           => 'uamswp_address',
                'title'        => 'Custom Address',
                // 'tab'       => 'faq',
                'type'         => 'group',
                'clone'        => true,
                'collapsible'  => true,
                'sort_clone'   => true,
                'group_title'  => array( 'field' => 'address_title' ),
                'max_clone'    => '2',
                'fields' => array(
                    array(
                        'name' => 'Address Name',
                        'id'   => 'address_title',
                        'type' => 'text',
                        'description'   => 'Leave blank for a single address',
                        'placeholder' => 'Mailing Address',
                    ),
                    array(
                        'name' => 'Address',
                        'id'   => 'address_street_1',
                        'type' => 'text',
                        'placeholder' => '4301 West Markham St #123',
                    ),
                    array(
                        'name' => 'Address (2)',
                        'id'   => 'address_street_2',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'City',
                        'id'   => 'address_city',
                        'type' => 'text',
                        'placeholder' => 'Little Rock',
                    ),
                    array(
                        'name' => 'State',
                        'id'   => 'address_state',
                        'type' => 'select',
                        'options' =>       array (
                            'AL' => 'Alabama',
                            'AK' => 'Alaska',
                            'AZ' => 'Arizona',
                            'AR' => 'Arkansas',
                            'CA' => 'California',
                            'CO' => 'Colorado',
                            'CT' => 'Connecticut',
                            'DE' => 'Delaware',
                            'DC' => 'District Of Columbia',
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
                          'std' =>       array (
                             'AR',
                          ),

                    ),
                    array(
                        'name' => 'Zip',
                        'id'   => 'address_zip',
                        'type' => 'text',
                        'size' => '10',
                        'placeholder' => '72205',
                    ),
                ),
                'hidden' => array( 
                    array('uamswp_template', '!=', 'institute'),
                    array('uamswp_location', '=', 'uams'),
                ),
            ),
        ),
    );

    $meta_boxes[] = array(
        'id'             => 'uamswp_phone',
        'title'          => 'Phone Number',
        'settings_pages' => 'uamswp_options',
        //'tab'            => 'faq',
        'fields'         => array(
            array(
                'type' => 'custom_html',
                'std'  => 'These will override the default social media',
            ),
            array(
                'id' => 'uamswp_primary_phone',
          	    'type' => 'tel',
                'name' => 'Primary Phone #',
                'placeholder' => '(501) 686-7000',
            ),
            array(
                'id' => 'uamswp_add_phones',
          	    'type' => 'url',
                'name' => 'Additional Phone Number(s) [URL]',
                'desc' => 'include http:// or https://'
            ),
        ),
        'validation' => array(
		    'rules'  => array(
		        'uamswp_primary_phone' => array(
		            'required'  => false,
		            'phoneUS' => true,
		        ),
            ),
        ),
    );

    // $meta_boxes[] = array(
    //     'id'             => 'uamswp_social',
    //     'title'          => 'Social Media',
    //     'settings_pages' => 'uamswp_options',
    //     //'tab'            => 'faq',
    //     'fields'         => array(
    //         array(
    //             'type' => 'custom_html',
    //             'std'  => 'These will override the default social media',
    //         ),
    //         array(
    //             'id' => 'uamswp_facebook',
    //       	    'type' => 'url',
    //       	    'name' => 'Facebook URL',
    //         ),
    //         array(
    //             'id' => 'uamswp_twitter',
    //       	    'type' => 'url',
    //       	    'name' => 'Twitter URL',
    //         ),
    //         array(
    //             'id' => 'uamswp_instagram',
    //       	    'type' => 'url',
    //       	    'name' => 'Instagram URL',
    //         ),
    //         array(
    //             'id' => 'uamswp_youtube',
    //       	    'type' => 'url',
    //       	    'name' => 'YouTube URL',
    //         ),
    //         array(
    //             'id' => 'uamswp_linkedin',
    //       	    'type' => 'url',
    //       	    'name' => 'LinkedIn URL',
    //         ),
    //         array(
    //             'id' => 'uamswp_pinterest',
    //       	    'type' => 'url',
    //       	    'name' => 'Pinterest URL',
    //         ),
    //     ),
    // );

    $meta_boxes[] = array(
        'id'             => 'info',
        'title'          => 'Theme Info',
        'settings_pages' => 'uamswp_options',
        'context'        => 'side',
        'fields'         => array(
            array(
                'type' => 'custom_html',
                'std'  => 'Having questions? Check out our documentation',
            ),
        ),
    );
    return $meta_boxes;
}