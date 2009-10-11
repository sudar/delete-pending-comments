<?php
/*
Plugin Name: Delete Pending Comments
Plugin URI: http://www.nicolaskuttler.de/wordpress/delete-pending-comments/
Author: Nicolas Kuttler
Author URI: http://www.nicolaskuttler.de/
Description: 
Version: 0.0.1
*/

add_action( 'init', 'nkdeletepending_init' );
function nkdeletepending_init() {
	$plugin_path = plugin_basename( dirname( __FILE__ ) .'/translations' );
	load_plugin_textdomain( 'delete-pending-comments', '', $plugin_path );
}

add_action('admin_menu', 'nkdeletepending_add_pages');
function nkdeletepending_add_pages() {
	add_options_page(__('Delete Pending', 'delete-pending-comments' ), __('Delete Pending', 'delete-pending-comments' ), 10, 'nkdeletepending', 'nkdeletepending_options_page');
	function nkdeletepending_options_page() { ?>
		<div class="wrap" style="margin: 5mm; max-width: 80ex;">
		<?php
			if ($_POST['nkdeletepending']) {
				echo '<div id="message" class="updated fade">Form submitted.<br />';
				if ($_POST['nkdeletepending']) {
					if ( stripslashes($_POST['nkdeletepending']) == __('I am sure I want to delete all pending comments and realize this can\'t be undone', 'delete-pending-comments' ) ) {
						_e('I deleted all pending comments!', 'delete-pending-comments' );
					}
					$comments = get_comments('status=hold');
					foreach ($comments as $comment) {
						wp_delete_comment($comment->comment_ID);
					}
				}
				echo '<br/>';
			echo '</div>';
			}
		?>
		<h2>Delete Pending Comments</h2>
		<p>
			<?php _e('You have to type the text following text into the form to delete all pending comments', 'delete-pending-comments' ); ?>:
		</p>
		<blockquote style="font-style: italic;" >
			<?php _e('I am sure I want to delete all pending comments and realize this can\'t be undone', 'delete-pending-comments' ); ?>.
		</blockquote>

		<form action="" method="post">
			<input name="nkdeletepending" type="text" size="80" >
			<input type="submit" class="button-primary" value="Delete comments">
		</form>

<h3>My other plugins</h3>
<p>     
<a href="http://www.nkuttler.de/nktagcloud/">Better tag cloud</a>:
I was pretty unhappy with the default WordPress tag cloud widget. This one is more powerful and offers a list HTML markup that is consistent with most other widgets.
<br/>
<a href="http://www.nkuttler.de/nkmovecomment/">Move WordPress comments</a>:
This plugin adds a small form to every comment on your blog. The form is only added for admins and allows you to move comments to a different post/page and to fix comment threading. 
<br/>
<a href="http://www.nkuttler.de/nksnow/">Snow and more</a>:
This one lets you see snowflakes (or other, custom images) fall down your blog.
<br/>
<a href="http://www.nkuttler.de/nkfireworks/">Fireworks</a>:
The name says it all, see snowflakes on your blog!
<br/>
<a href="http://www.rhymebox.de/blog/rhymebox-widget/">Rhyming widget</a>:
I wrote a little online rhyming dictionary. This is a widget to search it directly from one of your sidebars.
<br/>
</p> 
		</div>
		<?php
	}
}

?>
