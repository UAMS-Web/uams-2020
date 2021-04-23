<?php

/*
 *
 * Include ACF Pro
 * 
 */

// Define path and URL to the ACF plugin.
// define( 'MY_ACF_PATH', get_stylesheet_directory() . '/includes/acf/' );
// define( 'MY_ACF_URL', get_stylesheet_directory_uri() . '/includes/acf/' );

// Include the ACF plugin.
// include_once( MY_ACF_PATH . 'acf.php' );

// Customize the url setting to fix incorrect asset URLs.
// add_filter('acf/settings/url', 'my_acf_settings_url');
// function my_acf_settings_url( $url ) {
//     return MY_ACF_URL;
// }

// (Optional) Hide the ACF admin menu item.
// add_filter('acf/settings/show_admin', 'my_acf_settings_show_admin');
// function my_acf_settings_show_admin( $show_admin ) {
//     return false;
// }
add_filter( 'acf/fields/wysiwyg/toolbars' , 'my_toolbars'  );
function my_toolbars( $toolbars )
{
	// Uncomment to view format of $toolbars
	// echo '< pre >';
	// 	print_r($toolbars);
	// echo '< /pre >';
	// die;

    //Original Items
        // Array
        // (
        //     [Full] => Array
        //         (
        //             [1] => Array
        //                 (
        //                     [0] => formatselect
        //                     [1] => bold
        //                     [2] => italic
        //                     [3] => bullist
        //                     [4] => numlist
        //                     [5] => blockquote
        //                     [6] => alignleft
        //                     [7] => aligncenter
        //                     [8] => alignright
        //                     [9] => link
        //                     [10] => wp_more
        //                     [11] => spellchecker
        //                     [12] => fullscreen
        //                     [13] => wp_adv
        //                     [14] => |
        //                     [15] => geot_button
        //                 )

        //             [2] => Array
        //                 (
        //                     [0] => strikethrough
        //                     [1] => hr
        //                     [2] => forecolor
        //                     [3] => pastetext
        //                     [4] => removeformat
        //                     [5] => charmap
        //                     [6] => outdent
        //                     [7] => indent
        //                     [8] => undo
        //                     [9] => redo
        //                     [10] => wp_help
        //                 )

        //             [3] => Array
        //                 (
        //                 )

        //             [4] => Array
        //                 (
        //                 )

        //         )

        //     [Basic] => Array
        //         (
        //             [1] => Array
        //                 (
        //                     [0] => bold
        //                     [1] => italic
        //                     [2] => underline
        //                     [3] => blockquote
        //                     [4] => strikethrough
        //                     [5] => bullist
        //                     [6] => numlist
        //                     [7] => alignleft
        //                     [8] => aligncenter
        //                     [9] => alignright
        //                     [10] => undo
        //                     [11] => redo
        //                     [12] => link
        //                     [13] => fullscreen
        //                 )

        //         )

        // )
	

	// Add a new toolbar called "Very Simple"
	// - this toolbar has only 1 row of buttons
    // $toolbars['Very Simple' ] = array();
	// $toolbars['Very Simple' ][1] = array('bold' , 'italic' , 'underline' );
	$toolbars['Custom' ] = array();
	$toolbars['Custom' ][1] = array('formatselect' , 'bold' , 'italic' , 'bullist' , 'numlist' , 'blockquote' , 'alignleft' , 'aligncenter' , 'alignright' , 'link' , 'wp_adv'  );
    $toolbars['Custom' ][2] = array('hr' , 'pastetext' , 'removeformat' , 'charmap' , 'outdent' , 'indent' , 'undo' , 'redo' , 'wp_help' );
	// // Edit the "Full" toolbar and remove 'code'
	// // - delet from array code from http://stackoverflow.com/questions/7225070/php-array-delete-by-value-not-key
	// if( ($key = array_search('code' , $toolbars['Full' ][2])) !== false )
	// {
	//     unset( $toolbars['Full' ][2][$key] );
	// }

	// // remove the 'Basic' toolbar completely
	// unset( $toolbars['Basic' ] );

    // Uncomment to view format of $toolbars
	// echo '< pre >';
	// 	print_r($toolbars);
	// echo '< /pre >';
	// die;

	// return $toolbars - IMPORTANT!
	return $toolbars;
}

// add_filter( 'tiny_mce_before_init', function( $settings ){

// 	$settings['block_formats'] = 'Paragraph=p;Heading 3=h3;Heading 4=h4';

// 	return $settings;

// } );
add_action('acf/input/admin_footer', function() {
	?>
	<script type="text/javascript">
	acf.addFilter('wysiwyg_tinymce_settings', function( mceInit, id, field ){
		if( field.get('toolbar') == 'custom' ) {
			// do something.
            mceInit['block_formats'] = 'Paragraph=p;Heading 3=h3;Heading 4=h4;Heading 5=h5;';
		}
        console.log
		return mceInit;
	});
	</script>
	<?php
});