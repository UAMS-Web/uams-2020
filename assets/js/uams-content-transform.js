/**
 * Adds a "Transform to UAMS Section" option to the deprecated acf/uams-content block.
 *
 * Loaded in the <head> of the block editor so the filter is in place before ACF
 * registers its block types. The WYSIWYG HTML is converted to core blocks with
 * rawHandler; review the result before saving (anything that becomes a Classic or
 * Custom HTML block isn't in uams-section's allowed child list and should be redone).
 */
( function ( wp ) {
	if ( ! wp || ! wp.hooks || ! wp.blocks ) {
		return;
	}

	// ACF block data may be keyed by field name or (older saves) by field key.
	function pick( data, name, key ) {
		if ( data[ name ] !== undefined ) {
			return data[ name ];
		}
		if ( data[ key ] !== undefined ) {
			return data[ key ];
		}
		return '';
	}

	wp.hooks.addFilter(
		'blocks.registerBlockType',
		'uamswp/uams-content-to-section',
		function ( settings, name ) {
			if ( name !== 'acf/uams-content' ) {
				return settings;
			}
			var to = ( settings.transforms && settings.transforms.to ) || [];
			return Object.assign( {}, settings, {
				transforms: Object.assign( {}, settings.transforms, {
					to: to.concat( [
						{
							type: 'block',
							blocks: [ 'acf/uams-section' ],
							transform: function ( attributes ) {
								var d = attributes.data || {};
								var html = pick( d, 'content_content', 'field_content_description_b' );
								var innerBlocks = html ? wp.blocks.rawHandler( { HTML: html } ) : [];
								var newAttributes = {
									data: {
										section_heading: pick( d, 'content_heading', 'field_content_heading_b' ),
										_section_heading: 'field_section_heading_b',
										section_hide_heading: pick( d, 'content_hide_heading', 'field_content_hide_heading_b' ),
										_section_hide_heading: 'field_section_hide_heading_b',
										section_background_color: pick( d, 'content_background_color', 'field_content_background_color_b' ),
										_section_background_color: 'field_section_background_color_b',
									},
								};
								if ( attributes.anchor ) {
									newAttributes.anchor = attributes.anchor;
								}
								if ( attributes.className ) {
									newAttributes.className = attributes.className;
								}
								return wp.blocks.createBlock( 'acf/uams-section', newAttributes, innerBlocks );
							},
						},
					] ),
				} ),
			} );
		}
	);
} )( window.wp );
