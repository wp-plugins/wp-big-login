<?php
/**
 * @package   wp-big-login
 * @author    EXED Internet <service@exed.nl>
 * @license   GPL-2.0+
 * @link      http://exed.nl
 * @copyright 2014 EXED Internet
 *
 * @wordpress-plugin
 * Plugin Name: wp-big-login
 * Plugin URI: http://wordpress.org/plugins/wp-big-login/
 * Description: Let you login with a big number to a custom set URL
 * Version:     1.1.1
 * Author:      EXED Internet
 * Author URI:  http://exed.nl
 * License:     GPL-2.0+
 */


/**
 * Require the admin panel
 */
require_once( __DIR__ . '/settings.php' );

/**
 * Makes the widget ready for use
 */
class BIGRegisterLoginWidget extends WP_Widget {
	/**
	 * 
	 */
	function BIGRegisterLoginWidget() {
		$widget_ops = array( 'classname' => 'BIGRegisterLoginWidget', 'description' => 'This widget enables a login via BIG register http://www.bigregister.nl/' );
		$this->WP_Widget( 'BIGRegisterLoginWidget', 'BIG register login', $widget_ops );
	}

	/**
	 * Gets the widget title
	 */
	function form( $instance ){
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></label></p>
		<?php
	}
	/**
	 * Update the title widget
	 * @return Array instance
	 */
	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		return $instance;
	}
	

	/**
	* This is how te widget will look
	* @param array $args 
	* @param array $instance
	*/
	function widget( $args, $instance ){
		extract( $args, EXTR_SKIP );

		echo $before_widget;
		$title = empty( $instance['title'] ) ? '' : apply_filters( 'widget_title', $instance['title'] );

		if ( ! empty( $title  ) )
			echo $before_title . $title . $after_title;
		?>
		<form action="" method="post" id='BIGLogin'>
			<label for="big-nummer">BIG-nummer:</label>
			<input type="text" name="bignummer" id="big-nummer" placeholder=" <?php _e( 'Enter a valid BIG-Number', 'big-login' ); ?>" value="<?php echo ( isset( $_POST['bignummer']) ? $_POST['bignummer'] : '' );?>" />
			<strong>
				<?php if( isset( $_POST['bignummer'] ) ) _e( "<br />This isn't a valid BIG-Number<br /><br />", "big-login" );

				?>
			</strong>
			<input type="submit" name="" class="Send" value="Login" id="big-submit" />
		</form>
		<?php

		echo $after_widget;
	}
	/**
	 * @return object widget
	 */
	public function includeWidget( $args, $instance ) {
		return self::widget( $args, $instance, false );
	}
	
} //End class BIGRegisterLoginWidget

add_action( 'widgets_init', create_function( '', 'return register_widget( "BIGRegisterLoginWidget" );' ) );

/**
 * Looks if it is a valid big-nummer
 * @return bool
 */
function is_valid_BIG ( $big_code ) {
	require_once( dirname( __FILE__ ) . '/Ribiz/RibizSearch.class.php' );
	$RibizSearch = new Ribiz_Search();
	$RibizSearch->init();
	$results = $RibizSearch->search( array( 'RegistrationNumber' => abs( $big_code ) ) );
	if ( $results ) {
		return true;
	}
	return false;
}

add_action( 'init', 'big_login_init' );

/**
 * redirect to the next page when BIG-number is valid
 */
function big_login_init() {
	if ( ! session_id() ) {
		session_start();
	}
	if ( $_POST && isset( $_POST['bignummer'] ) ) {
		if ( is_valid_BIG( $_POST['bignummer'] ) ) {
			$_SESSION['professional'] = true;
			wp_redirect( get_option( 'big-login-url', '/' ) ); //redirect after login
			exit;
		}
	}
}
