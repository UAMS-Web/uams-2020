<?php
/**
 * Adds a media credit to images in the media library
 */

// Add fields

	add_filter( 'attachment_fields_to_edit', 'uamswp_attachment_fields_to_edit_source', 10, 2 );

	function uamswp_attachment_fields_to_edit_source( $form_fields, $post ) {

		// Media Credit

			$file = get_attached_file( $post->ID );
			$media_meta = wp_read_image_metadata($file);
			$media_credit = get_post_meta( $post->ID, '_media_credit', true );

			if ( empty($media_credit) ) {

				$media_credit = $media_meta["credit"];

			}

			$form_fields['media_credit'] = array(
				'label' => esc_attr( 'Image Credit' ),
				'input' => 'text',
				'value' => esc_attr( $media_credit ),
				'helps' => 'Original Credit: ' . esc_html( $media_meta["credit"] ),
			);

		return $form_fields;

	}

// Save fields

	add_filter( 'attachment_fields_to_save', 'uamswp_attachment_fields_to_save_source_url_name', 10, 2 );

	function uamswp_attachment_fields_to_save_source_url_name( $post, $attachment ) {

		// Source Name

			if ( isset( $attachment['media_credit'] ) ) {

				// Get previous value

					$media_credit = get_post_meta( $post['ID'], '_media_credit', true );

				// If there is a change

					if ( $media_credit !== $attachment['media_credit'] ) {

						// Delete

							if ( empty( $attachment['media_credit'] ) ) {

								delete_post_meta( $post['ID'], '_media_credit' );

							}

						// Update

							else {

								update_post_meta( $post['ID'], '_media_credit', $attachment['media_credit'] );

							}

					}

			}

		return $post;

	}

// Filter images to include source link

	// Filter captioned images to include source link

		/**
		 * Source: https://gist.github.com/adamcapriola/34d497f83e4436b20eaa35964ba1f800
		 */

		/*
		add_filter( 'img_caption_shortcode', 'ac_add_sources_captioned', 10, 3 );

		function ac_add_sources_captioned( $empty, $attr, $content ) {

			$atts = shortcode_atts( array(
				'id'	  => '',
				'align'	  => '',
				'width'	  => '',
				'caption' => '',
				'class'   => '',
			), $attr, 'caption' );
			$post_id = explode( '_', esc_attr( $atts['id'] ) );
			$media_credit = get_post_meta( $post_id[1], '_media_credit', true );

			// We have a source

				if ( ! empty( $media_credit ) ) {

					// Code from /wp-includes/media.php

						$atts['width'] = (int) $atts['width'];

					if ( $atts['width'] < 1 || empty( $atts['caption'] ) ) {

						return $content;

					}

					if ( ! empty( $atts['id'] ) ) {

						$atts['id'] = 'id="' . esc_attr( $atts['id'] ) . '" ';

					}

					$class = trim( 'wp-caption ' . $atts['align'] . ' ' . $atts['class'] );

					// Return output along with source

						return '<figure ' . $atts['id'] . 'style="width: ' . (int) $atts['width'] . 'px;" class="' . esc_attr( $class ) . '"><span class="image-wrap">' . do_shortcode( $content ) . '</span><figcaption class="wp-caption-text">' . $atts['caption'] . ' <span class="wp-media-credit">(' . $media_credit . ')</span></figcaption></figure>';

				}

			// No source

				else {

					return '';

				}

		}
		*/

	// Filter non-captioned images to include source link

		/**
		 * Source: https://gist.github.com/adamcapriola/ce1562700e148e7c9afbadc00afc09f3
		 */

		/*
		add_filter( 'the_content', 'ac_add_sources_non_captioned', 11 );

		function ac_add_sources_non_captioned( $content ) {

			if ( is_singular( array( 'post', ) ) ) {

				$search = $replace = array();

				// Match non-captioned images

					if (
						preg_match_all(
							'/(<p[^>]*?>\s*)((?:<a [^>]+>\s*)?<img [^>]+)wp-image-(\d+)([^>]+>(?:\s*<\/a>)?)/',
							$content,
							$matches,
							PREG_SET_ORDER
						)
					) {

						foreach ( $matches as $match ) {

							$source_url = get_post_meta( $match[3], '_wp_attachment_source_url', true );

							// We have a source

								if ( ! empty( $source_url ) ) {

									// Source name

									$source_name = get_post_meta( $match[3], '_wp_attachment_source_name', true );

									if ( empty( $source_name ) ) {

										$parsed_url = parse_url( $source_url );
										$source_name = str_replace( 'www.', '', $parsed_url['host'] );

									}

									// Alignment

									if ( false !== ( strpos( $match[2], 'alignnone' ) || strpos( $match[4], 'alignnone' ) ) ) {

										$alignment = 'none';
										$style = '';

									}

									elseif ( false !== ( strpos( $match[2], 'alignright' ) || strpos( $match[4], 'alignright' ) ) ) {

										$alignment = 'right';
										$style = '';

									}

									elseif ( false !== ( strpos( $match[2], 'alignleft' ) || strpos( $match[4], 'alignleft' ) ) ) {

										$alignment = 'left';
										$style = '';

									}

									elseif ( false !== ( strpos( $match[2], 'aligncenter' ) || strpos( $match[4], 'aligncenter' ) ) ) {

										$alignment = 'center';

										// get width!

											if ( preg_match( '/width="(\d+)"/', $match[4], $width ) ) {

												$style = ' style="width:' . $width[1] . 'px;"';

											}

											else {

												$style = '';

											}

									}

									else {

										continue;

									}

									// Build search and replace arrays

										$search[] = '%' . preg_quote( $match[1] . $match[2] . 'wp-image-' . $match[3] . $match[4], '%' ) . '%';
										$replace[] = $match[1] . '<span class="image-wrap image-wrap-' . $alignment . '"' . $style . '>' . $match[2] . 'wp-image-' . $match[3] . $match[4] . '<span class="source"><a href="' . esc_url( $source_url ) . '" target="_blank">' . $source_name . '</a></span></span>';
								}
						}
					}

				// preg_replace

					if ( ! empty ( $search ) ) {

						$content = preg_replace( $search, $replace, $content );

					}

			}

			return $content;

		}
		*/

function uamswp_add_media_credit( $id ) {
    $media_credit = get_post_meta( $id, '_media_credit', true );
    if ( ! empty( $media_credit ) ) {
        return $media_credit;
    }
    
}

// function uamswp_core_image_block_render( $attributes, $content ) {
// 	$photo_credit = uamswp_add_media_credit( $attributes['id'] );
// 	if ( $photo_credit ) {
// 		if ( strpos( $content, '<figcaption>' ) !== false ) {
// 			$content = str_replace( '</figcaption>', ' <span class="photo-credit">(' . esc_html__( 'Image credit:' ) . ' ' . esc_html( $photo_credit ) . ')</span></figcaption>', $content );
// 		} else {
// 			$content = str_replace( '</figure>', '<figcaption><span class="photo-credit">(' . esc_html__( 'Image credit:' ). ' ' . esc_html( $photo_credit ) . ')</span></figcaption></figure>', $content );
// 		}
// 	}

// 	return $content;
// }
// function uamswp_register_core_image_block() {
// 	register_block_type( 'core/image', array(
// 		'render_callback' => 'uamswp_core_image_block_render',
// 	) );
// }
// add_action( 'init', 'uamswp_register_core_image_block' );

/* Append photo credit to image block with render_block filter
 * Replaces incorrect register block call 
 */
function uamswp_core_image_add_credits($block_content, $block, $instance) {
	if ($block['blockName'] == 'core/image') {
		$photo_credit = get_post_meta( $block['attrs']['id'], '_media_credit', true );
		if (empty($photo_credit)) {
			$photo_credit = wp_get_attachment_metadata($block['attrs']['id'])['image_meta']['credit'];
		}
		if( $photo_credit ) {
			if ( strpos( $block_content, '<figcaption>' ) !== false ) {
				$block_content = str_replace( '</figcaption>', ' <span class="photo-credit">(' . esc_html__( 'Image credit:' ) . ' ' . esc_html( $photo_credit ) . ')</span></figcaption>', $block_content );
			} else {
				$block_content = str_replace( '</figure>', '<figcaption><span class="photo-credit">(' . esc_html__( 'Image credit:' ). ' ' . esc_html( $photo_credit ) . ')</span></figcaption></figure>', $block_content );
			}
		}
	}

	return $block_content;
}
add_filter('render_block', 'uamswp_core_image_add_credits', 10, 3);