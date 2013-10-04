<?php

/**
 * Make admin panel page
 */
add_action( 'admin_menu', 'big_login_admin_menu_init' );

function big_login_admin_menu_init() {
	global $big_login;
	$test = __( 'big login', 'big-login' );
	$big_login = add_menu_page( $test, $test, 'manage_options', 'big-login-settings', 'big_login_settings' );

}

/**
 * Settings on the admin panel page
 */
function big_login_settings() {


	if ( isset( $_POST['url'] ) ) {
		//Checks if the url is valid. 
		if ( '' == ( $url = esc_url( $_POST['url'] ) ) ) {
?>
	<div id="welcomeDiv" class="answer_list error" > <?php _e( 'Invalid URL', 'big-login' ); ?></div>
<?php
		} else {
			big_login_save( $url );
		}
	}
	$value = get_option( "big-login-url" );
?>

<!--The admin panel page-->
	<div class="wrap">
	<div id="icon-generic" class="icon32"><br /></div>
	<h2><?php _e( 'Big login', 'big-login' ); ?></h2>
	<form action="" method="post" id="BIGLogin" onClick="showBox();">
	<h3><?php _e( 'Change url', 'big-login' ); ?></h3>
		<?php _e( 'This is the admin panel for the BIG-Register plugin. Here you can specify a
		URL where you will be redirect to. <hr /> <strong> <br />
		example: <br /> www.domain.nl <br /> / path / </strong>', 'big-login' ); ?>
</br>
<table>
	<tr>
	<td>
		<label for="url"><?php _e( 'URL after Login:', 'big-login' ); ?></label>
	</td>
	<td>
		<input type="text" name="url" id="url" placeholder="<?php _e( 'Enter a valid URL.', 'big-login' ); ?> " value="<?php echo ( isset( $value ) ? $value : '' ); ?>" required="required" />
	</td>
	<td>
		<input type="submit" name="" class="Send" value="<?php _e( 'Save', 'big-login' ); ?>" id="big-submit" onclick="showDiv()" /> <br />
	</td>
	</tr>
</table>
</form>

<!--Check if you've saved the url-->
	<?php if ( isset( $_POST["url"] ) ): ?>
		<div id="welcomeDiv" class="answer_list" ><?php _e( 'Saved', 'big-login' ); ?></div>
	<?php endif; ?>
</div>

<?php
}

/**
 * Shows the current BIG-login URL
 */
function big_login_save( $url ) {
	update_option( 'big-login-url', $url );
}
