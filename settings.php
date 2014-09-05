<?php

/**
 * Make admin panel page
 */
add_action( 'admin_menu', 'big_login_admin_menu_init' );

function big_login_admin_menu_init() {
	global $big_login;
	$title = __( 'Big login', 'big-login' );
	$big_login = add_menu_page( $title, $title, 'manage_options', 'big-login-settings', 'big_login_settings' );
}

/**
 * Settings on the admin panel page
 */
function big_login_settings() {
	$saved = null;
	if ( isset( $_POST['url'] ) ) {
		//Checks if the url is valid. 
		if ( '' !== ( $url = esc_url( $_POST['url'] ) ) ) {
			big_login_save( $url );
			$saved = true;
		} else {
			$saved = false;
		}
	}
	$value = get_option( "big-login-url" );
?>

	<!--The admin panel page-->
	<div class="wrap">
		<h2><?php _e( 'Big login', 'big-login' ); ?></h2>
		<!--Check if you've saved the url-->
		<?php if ( true === $saved ): ?>
			<div class="updated">
		        <p><?php _e( 'Saved!', 'big-login' ); ?></p>
		    </div>
		<?php elseif ( false === $saved ): ?>
			<div class="error">
				<p><?php _e( 'Invalid URL', 'big-login' ); ?></p>
			</div>
		<?php endif;?>
		<form action="" method="post" id="BIGLogin" onClick="showBox();">
			<h3><?php _e( 'Change url', 'big-login' ); ?></h3>
				<?php _e( 'This is the admin panel for the BIG-Register plugin. Here you can specify a
				URL where you will be redirect to. <hr /> <strong> <br />
				example: <br /> www.domain.nl <br /> /path/ </strong>', 'big-login' ); ?>
			<br />
			<table>
				<tr>
					<td>
						<label for="url"><?php _e( 'URL after Login:', 'big-login' ); ?></label>
					</td>
					<td>
						<input type="text" name="url" id="url" placeholder="<?php _e( 'Enter a valid URL.', 'big-login' ); ?> " value="<?php echo ( isset( $value ) ? $value : '' ); ?>" required="required" />
					</td>
					<td>
						<?php submit_button( __( 'Save', 'big-login' ), 'primary', '', false, 
								array (
									'id' => 'big-submit',
									'onClick' => "showDiv()" 
								) 
							); 
						?>
					</td>
				</tr>
			</table>
		</form>
	</div>
<?php
}

/**
 * Shows the current BIG-login URL
 */
function big_login_save( $url ) {
	update_option( 'big-login-url', $url );
}
