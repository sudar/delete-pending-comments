<?php

/**
 * Load translations
 *
 * @since 0.1.0
 */
function nkdeletepending_load_translation_file() {
	$plugin_path = plugin_basename( dirname( __FILE__ ) . '/../translations' );
	load_plugin_textdomain( 'delete-pending-comments', '', $plugin_path );
}

/**
 * Add admin page.
 */
function nkdeletepending_add_pages() {
	add_submenu_page( 'edit-comments.php', __( 'Delete Pending Comments', 'delete-pending-comments' ), __( 'Delete Pending Comments', 'delete-pending-comments' ), 'manage_options', 'delete-pending-comments', 'nkdeletepending_options_page' );
}

/**
 * The admin page
 */
function nkdeletepending_options_page() {
	global $wpdb;

	$magic_string = __( "I am sure I want to delete all pending comments and realize this can't be undone", 'delete-pending-comments' );

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>

	<div class="wrap">
	<h2>
		<?php _e( 'Delete Pending Comments', 'delete-pending-comments' ) ?>
	</h2>

	<?php
	if ( isset( $_POST['nkdeletepending'] ) ) {
		if ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'delete-pending-comments' ) ) {
			die( 'Security check' );
		}

		if ( stripslashes( $_POST['nkdeletepending'] ) == $magic_string ) {
			$wpdb->query( "DELETE FROM $wpdb->comments WHERE comment_approved = 0" );
			?>

			<div class="updated notice">
				<p>
					<?php _e( 'All pending and spam comments are deleted successfully from your site.', 'delete-pending-comments' ); ?>
				</p>
			</div>
			<?php
		} else {
			?>
			<div class="error">
				<p>
					<?php _e( 'Please try again. Did you copy the text properly?', 'delete-pending-comments' ); ?>
				</p>
			</div>
		  <?php
		}
	} else {

		$pending_comment_ids    = $wpdb->get_col( "SELECT comment_ID FROM $wpdb->comments WHERE comment_approved = 0" );
		$pending_comments_count = count( $pending_comment_ids );

		if ( $pending_comments_count > 0 ) {
			?>
			<p>
				<?php
				printf(
					_n(
						'You have %s pending comment in your site. Do you want to delete it?',
						'You have %s pending comments in your site. Do you want to delete all of them?',
						$pending_comments_count,
						'delete-pending-comments'
					),
					number_format_i18n( $pending_comments_count )
				);
				?>
			</p>

			<p>
				<?php _e( 'You have to type the following text into the textbox to delete all the pending comments:', 'delete-pending-comments' ); ?>
			</p>

			<blockquote>
				<em>
					<?php echo $magic_string ?>
				</em>
			</blockquote>

			<form action="" method="post">
				<?php wp_nonce_field( 'delete-pending-comments' ); ?>
				<input name="nkdeletepending" type="text" size="80">
				<p class="submit">
					<input type="submit" class="button-primary"
					       value="<?php _e( 'Delete Pending Comments', 'delete-pending-comments' ); ?>">
				</p>
			</form>
			<?php
		} else {
			?>
			<p>
				<?php _e( 'There are no pending or spam comments in your site.', 'delete-pending-comments' ); ?>
			</p>
			<?php
		}
		?>
	</div>
	<?php
	}
}