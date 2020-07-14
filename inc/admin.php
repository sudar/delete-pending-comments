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
	<link rel="stylesheet" href="<?php echo get_bloginfo( 'home' ) . '/' . PLUGINDIR . '/delete-pending-comments/css/admin.css' ?>" type="text/css" media="all" /> <?php
}

/**
 * Add admin page and CSS
 */
function nkdeletepending_add_pages() {
	$page = add_submenu_page( 'edit-comments.php', __( 'Delete Pending Comments', 'delete-pending-comments' ), __( 'Delete Pending Comments', 'delete-pending-comments' ), 10, 'delete-pending-comments', 'nkdeletepending_options_page' );
	add_action( 'admin_head-' . $page, 'nkdeletepending_css_admin' );

	// Add icon
	add_filter( 'ozh_adminmenu_icon_delete-pending-comments', 'delete_pending_comments_icon' );
}

/**
 * Return admin menu icon
 *
 * @return string path to icon
 *
 * @since 0.1.0.1
 */
function delete_pending_comments_icon() {
	return get_bloginfo( 'home' ) . '/' . PLUGINDIR . '/delete-pending-comments/pic/comment_delete.png';
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

			// Limit. Timeout with 10k comments...
			// We only need this to see if there are any pending anyway
			$comments = get_comments( 'status=hold&number=1' );

			if ( $comments ) {
				if ( stripslashes( $_POST['nkdeletepending'] ) == $magic_string ) {

					/* This was waaaay to slow with 10k+ comments
					/*
					foreach ( $comments as $comment ) {
						wp_delete_comment( $comment->comment_ID );
					}
					*/
					global $wpdb;
					$wpdb->query( 
						$wpdb->prepare( "DELETE FROM $wpdb->comments WHERE comment_approved = 0" )
					);
					$deleted_comment_ids = wp_list_pluck( $comments, 'comment_ID' );
					clean_comment_cache( $deleted_comment_ids );
					foreach ( $deleted_comment_ids as $comment_id ) {
						do_action( 'wp_set_comment_status', $comment_id, 'delete' );
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

		<h2><?php _e( 'Delete Pending Comments', 'delete-pending-comments' ) ?></h2>

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
