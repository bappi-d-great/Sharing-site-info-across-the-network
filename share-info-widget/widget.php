<?php

namespace shareinfo;
use \WP_Widget as WP_Widget;
 
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'Share_Info_Widget' ) ) {
	
	class Share_Info_Widget extends WP_Widget{
		
		/**
		 * Sets up the widgets name etc
		 */
		public function __construct() {
			parent::__construct(
				'share_info_widget', // Base ID
				__( 'Share Static Content across Multisite' ), // Name
				array( 'description' => __( 'A widget to share static content' ), ) // Args
			);
		}
		
		
		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			
			echo $args['before_widget'];
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
                                $sites = wp_get_sites();
                                $info = get_site_option( 'share_site_info' );
                                $id = get_current_blog_id();
                                
                                foreach( $sites as $site ){
                                    if( $site['blog_id'] == $id ) continue;
                                    switch_to_blog( $site['blog_id'] );
                                    ?>
                                    <ul>
                                        <li>
                                            <h4><?php echo get_bloginfo( 'name' ); ?></h4>
                                            <p><?php echo $info[$site['blog_id']] ?></p>
                                        </li>
                                    </ul>
                                    <?php
                                    restore_current_blog();
                                }
                                
			}
			echo $args['after_widget'];
			
		}
		
		
		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			
			$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Site Information' );
			
			?>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			
			<?php
			
		}


		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			
			$instance = array();
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			
	
			return $instance;
			
		}
		
	}

	add_action( 'widgets_init', function() {
		register_widget( 'shareinfo\Share_Info_Widget' );
	} );
	
}
	