<?php
/**
 * UAMSWP Widgets
 *
 * @package      UAMSWP 2020
 * @since        1.0
 * @link         http://webdevsuperfast.github.io
 * @author       Rotsen Mark Acob <webdevsuperfast.github.io>
 * @copyright    Copyright (c) 2015, Rotsen Mark Acob
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
*/

/**
 * Custom ACF widgets.
 */
class UAMSWP_Call_Out_Widget extends WP_Widget {

    /**
    * Register widget with WordPress.
    */
    function __construct() {
        parent::__construct(
            'uamswp_callout_widget', // Base ID
            __('UAMS Callout Widget', 'uamswp-uams-2020'), // Name
            array( 'description' => __( 'UAMS call out widget', 'uamswp-uams-2020' ), 'classname' => 'uamswp-callout-widget' ) // Args
        );
    }

    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        // if ( !empty($instance['title']) ) {
        //     echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		// }
		
		// widget ID with prefix for use in ACF API functions
        $widget_id = 'widget_' . $args['widget_id'];

        $heading = get_field('call_out_heading', $widget_id);
        $body = get_field('call_out_body', $widget_id);
        $use_image = get_field('call_out_use_image', $widget_id);
        $image = get_field('call_out_image', $widget_id);
        $background_color = get_field('call_out_background_color', $widget_id);
        
        include( get_stylesheet_directory() .'/blocks/call-out.php' );

        echo $args['after_widget'];
    }

    /**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
    public function form( $instance ) {
        if ( isset($instance['title']) ) {
            $title = $instance['title'];
        }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (Not Displayed)' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php
    }

    /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class UAMSWP_Call_Out_Widget

class UAMSWP_CTA_Widget extends WP_Widget {

    /**
    * Register widget with WordPress.
    */
    function __construct() {
        parent::__construct(
            'uamswp_cta_widget', // Base ID
            __('UAMS Call-to-Action Widget', 'uamswp-uams-2020'), // Name
            array( 'description' => __( 'UAMS call to action widget', 'uamswp-uams-2020' ), 'classname' => 'uamswp-cta-widget' ) // Args
        );
    }

    /**
    * Front-end display of widget.
    *
    * @see WP_Widget::widget()
    *
    * @param array $args     Widget arguments.
    * @param array $instance Saved values from database.
    */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        // if ( !empty($instance['title']) ) {
        //     echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		// }
		
		// widget ID with prefix for use in ACF API functions
        $widget_id = 'widget_' . $args['widget_id'];

        $heading = get_field('cta_bar_heading', $widget_id);
        $body = get_field('cta_bar_body', $widget_id);
        $button_text = get_field('cta_bar_button_text', $widget_id);
        $button_url = get_field('cta_bar_button_url', $widget_id);
        $button_target = get_field('cta_bar_button_target', $widget_id);
        $button_desc = get_field('cta_bar_button_description', $widget_id);
        $layout = get_field('cta_bar_layout', $widget_id);
        $use_image = get_field('cta_bar_use_image', $widget_id);
        $image = get_field('cta_bar_image', $widget_id);
        $background_color = get_field('cta_bar_background_color', $widget_id);
        
        include( get_stylesheet_directory() .'/blocks/cta.php' );

        echo $args['after_widget'];
    }

    /**
    * Back-end widget form.
    *
    * @see WP_Widget::form()
    *
    * @param array $instance Previously saved values from database.
    */
    public function form( $instance ) {
        if ( isset($instance['title']) ) {
            $title = $instance['title'];
        }
    ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title (Not Displayed)' ); ?></label>
      <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
    </p>
    <?php
    }

    /**
    * Sanitize widget form values as they are saved.
    *
    * @see WP_Widget::update()
    *
    * @param array $new_instance Values just sent to be saved.
    * @param array $old_instance Previously saved values from database.
    *
    * @return array Updated safe values to be saved.
    */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;
    }

} // class UAMSWP_CTA_Widget

// register UAMSWP widgets
add_action( 'widgets_init', function(){
  register_widget( 'UAMSWP_Call_Out_Widget' );
  register_widget( 'UAMSWP_CTA_Widget' );
});