<?php
/*
Plugin Name: Delete Pending Comments
Plugin URI: http://www.nkuttler.de/wordpress/delete-pending-comments/
Author: Nicolas Kuttler
Author URI: http://www.nkuttler.de/
Description: 
Version: 0.0.3
*/

add_action( 'init', 'nkdeletepending_init' );
function nkdeletepending_init() {
	$plugin_path = plugin_basename( dirname( __FILE__ ) .'/translations' );
	load_plugin_textdomain( 'delete-pending-comments', '', $plugin_path );
}

add_action('admin_menu', 'nkdeletepending_add_pages');
function nkdeletepending_add_pages() {
	add_options_page(__('Delete Pending Comments', 'delete-pending-comments' ), __('Delete Pending Comments', 'delete-pending-comments' ), 10, 'nkdeletepending', 'nkdeletepending_options_page');
	function nkdeletepending_options_page() { ?>
		<div class="wrap" style="margin: 5mm; max-width: 80ex;">
		<?php
			if ($_POST['nkdeletepending']) {
				echo '<div id="message" class="updated fade">';
				if ($_POST['nkdeletepending']) {
					if ( stripslashes($_POST['nkdeletepending']) == __('I am sure I want to delete all pending comments and realize this can\'t be undone', 'delete-pending-comments' ) ) {
						_e('I deleted all pending comments!', 'delete-pending-comments' );
					}
					else {
						_e('Please try again. Did you copy the text properly?', 'delete-pending-comments' );
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
		<h2><?php _e('Delete Pending Comments', 'delete-pending-comments') ?></h2>
		<p>
			<?php _e('You have to type the following text into the form to delete all pending comments', 'delete-pending-comments' ); ?>
		</p>
		<blockquote style="font-style: italic;" >
			<?php _e('I am sure I want to delete all pending comments and realize this can\'t be undone', 'delete-pending-comments' ); ?>
		</blockquote>

		<form action="" method="post">
			<input name="nkdeletepending" type="text" size="80" >
			<input type="submit" class="button-primary" value="<?php _e('Delete Pending Comments', 'delete-pending-comments') ?>">
		</form>

<h3>My plugins</h3>
<p>
<a href="http://www.nkuttler.de/wordpress/nktagcloud/">Better tag cloud</a>:
I was pretty unhappy with the default WordPress tag cloud widget. This one is more powerful and offers a list HTML markup that is consistent with most other widgets.
<br/>
<a href="http://www.nkuttler.de/wordpress/nkthemeswitch/">Theme switch</a>:
I like to tweak my main theme that I use on a variety of blogs. If you have ever done this you know how annoying it can be to break things for visitors of your blog. This plugin allows you to use a different theme than the one used for your visitors when you are logged in.
<br/>
<a href="http://www.nkuttler.de/wordpress/zero-conf-mail/">Zero Conf Mail</a>:
Simple mail contact form, the way I like it. No ajax, no bloat. No configuration necessary, but possible.
<br/>
<a href="http://www.nkuttler.de/wordpress/nkmovecomments/">Move WordPress comments</a>:
This plugin adds a small form to every comment on your blog. The form is only added for admins and allows you to <a href="http://www.nkuttler.de/nkmovecomments/">move comments</a> to a different post/page and to fix comment threading.
<br/>
<a href="http://www.nkuttler.de/wordpress/delete-pending-comments">Delete Pending Comments</a>:
This is a plugin that lets you delete all pending comments at once. Useful for spam victims.
<br/>
<a href="http://www.nkuttler.de/wordpress/nksnow/">Snow and more</a>:
This one lets you see snowflakes, leaves, raindrops, balloons or custom images fall down or float upwards on your blog.
<br/>
<a href="http://www.nkuttler.de/wordpress/nkfireworks/">Fireworks</a>:
The name says it all, see fireworks on your blog!
<br/>
<a href="http://www.rhymebox.de/blog/rhymebox-widget/">Rhyming widget</a>:
I wrote a little online <a href="http://www.rhymebox.com/">rhyming dictionary</a>. This is a widget to search it directly from one of your sidebars.
</p>
		</div>
		<?php
	}
}

?>
