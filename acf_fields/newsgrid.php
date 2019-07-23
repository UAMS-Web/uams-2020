<?php
/*
 *
 * News Grid  
 * 
 */
if (class_exists('UAMS_Syndicate_News_Base')) {
    return array(
        array(
            'key' => 'field_news_category',
            'label' => 'News Category',
            'name' => 'news_category',
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
            'key' => 'field_news_offset',
            'label' => 'News Offset',
            'name' => 'news_offset',
            'type' => 'number',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'default_value' => 0,
            'placeholder' => 0,
            'prepend' => '',
            'append' => '',
            'min' => 0,
            'max' => 9,
            'step' => 1,
        ),
    );
} else {
    return array(
        array(
            'key' => 'field_news_not_available',
			'label' => 'Not Available',
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
			'message' => 'This module is not available, please activate News Syndication plugin.',
			'new_lines' => 'wpautop',
			'esc_html' => 0,
        ),
    );
}