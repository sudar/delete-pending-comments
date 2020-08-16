<?php
/**
 * Plugin Name: Delete Pending Comments
 * Plugin URI: https://bulkwp.com
 * Author: sudar
 * Author URI: https://sudarmuthu.com/
 * Description: A quick way to delete all pending and spam comments. Useful for victims of spammer attacks.
 * Version: 1.0.0
 * Text Domain: delete-pending-comments
 * Domain Path: translations/
 * === RELEASE NOTES ===
 * Check readme file for full release notes.
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