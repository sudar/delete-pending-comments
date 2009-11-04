<?php
/*
Plugin Name: Delete Pending Comments
Plugin URI: http://www.nkuttler.de/wordpress/delete-pending-comments/
Author: Nicolas Kuttler
Author URI: http://www.nkuttler.de/
Description: A quick way to delete all pending comments. Useful for victims of spammer attacks.
Version: 0.2.1
Text Domain: delete-pending-comments
*/

/**
 * Check if we are in admin
 *
 * @since 0.1.0
 */
function nkdeletepending_load() {
	if ( is_admin() ) {
		require_once( 'inc/admin.php' );
		add_action( 'init', 'nkdeletepending_load_translation_file' );
		add_action( 'admin_menu', 'nkdeletepending_add_pages' );
	}
}
nkdeletepending_load();

?>
