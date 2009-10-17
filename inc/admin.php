<?php

/**
 * Load translations
 *
 * @since 0.1.0
 */
function nkdeletepending_load_translation_file() {
	$plugin_path = plugin_basename( dirname( __FILE__ ) .'/../translations' );
	load_plugin_textdomain( 'delete-pending-comments', '', $plugin_path );
}

/**
 * Load admin CSS style
 *
 * @since 0.1.0
 *
 * @todo check if the path is correct
 */
function nkdeletepending_css_admin() { ?>
	<link rel="stylesheet" href="<?php echo get_bloginfo( 'home' ) . '/' . PLUGINDIR . '/delete-pending-comments/inc/admin.css' ?>" type="text/css" media="all" /> <?php
}

/**
 * Add admin page and CSS
 */
function nkdeletepending_add_pages() {
	$page = add_submenu_page( 'edit-comments.php', __( 'Delete Pending Comments', 'delete-pending-comments' ), __( 'Delete Pending Comments', 'delete-pending-comments' ), 10, 'delete-pending-comments', 'nkdeletepending_options_page' );
	add_action( 'admin_head-' . $page, 'nkdeletepending_css_admin' );
}

/**
 * The admin page
 */
function nkdeletepending_options_page() {
	$magic_string = __("I am sure I want to delete all pending comments and realize this can't be undone", 'delete-pending-comments' );
	if ( current_user_can( 'manage_options' ) ) { ?>
		<div class="wrap" > <?php
		if ( $_POST['nkdeletepending'] ) {
			#function_exists( 'check_admin_referer' ) ? check_admin_referer( 'delete-pending-comments' ) : null;
			$nonce = $_REQUEST['_wpnonce'];
			if ( !wp_verify_nonce( $nonce, 'delete-pending-comments' ) ) die( 'Security check' );

			$comments = get_comments( 'status=hold' );

			if ( $comments ) {
				if ( stripslashes( $_POST['nkdeletepending'] ) == $magic_string ) {
					foreach ( $comments as $comment ) {
						wp_delete_comment( $comment->comment_ID );
					}
					echo '<div class="updated">';
					_e( 'I deleted all pending comments!', 'delete-pending-comments' );
					echo '</div>';
				}
				else {
					echo '<div class="error">';
					_e( 'Please try again. Did you copy the text properly?', 'delete-pending-comments' );
					echo '</div>';
				}
			}
			else {
				echo '<div class="error">';
				_e( 'It looks like there aren\'t any pending comments!', 'delete-pending-comments' );
				echo '</div>';
			}
		} ?>

		<h2><?php _e( 'Delete Pending Comments', 'delete-pending-comments' ) ?></h2> <?php
		require_once( 'nkuttler.php' );
		nkuttler0_2_1_links( 'delete-pending-comments' ); ?>
		<p>
			<?php _e( 'You have to type the following text into the form to delete all pending comments:', 'delete-pending-comments' ); ?>
		</p>

		<blockquote>
			<?php echo $magic_string ?>
		</blockquote>
	
		<form action="" method="post">
			<?php function_exists( 'wp_nonce_field' ) ? wp_nonce_field( 'delete-pending-comments' ) : null; ?>
			<input name="nkdeletepending" type="text" size="80" >
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Delete Pending Comments', 'delete-pending-comments' ) ?>">
			</p>
		</form>
		</div>
		<?php
	}
}

?>
