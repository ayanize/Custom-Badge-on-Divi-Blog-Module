<?php
function divi_new_blog_setup() {
  if ( class_exists('ET_Builder_Module')) {
      include ( 'blog-new.php' );
	  $new = new ET_Builder_Module_Blog_New();
      remove_shortcode( 'et_pb_blog' );
      add_shortcode('et_pb_blog', array($new, '_shortcode_callback'));
	 }
}
add_action('wp', 'divi_new_blog_setup', 9999);



add_action('add_meta_boxes', 'add_badge_mb_post');
function add_badge_mb_post()
{
    add_meta_box('custom-badge-id', 'Badge', 'badge_meta_box_cb', 'post', 'side', 'low');
}
function badge_meta_box_cb($post)
{
    $values = get_post_custom($post->ID);
    $path   = isset($values['badge_url_field']) ? esc_url($values['badge_url_field'][0]) : '';
?>
        <input type="text" placeholder="Complete Image URL Path" name="badge_url_field" id="badge_url_field" value="<?php
    echo $path;
?>" />
		<style>#badge_url_field {width: 100%;}</style>

<?php
}
add_action('save_post', 'save_badge_url');
function save_badge_url()
{
    global $post;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;
    $field = $_POST['badge_url_field'];
    update_post_meta($post->ID, 'badge_url_field', $field);
}
function call_badge_blog_index()
{
    global $post;
    $img  = get_post_meta($post->ID, 'badge_url_field', true);
    $html = '<img style="max-width:20%; min-width: auto;position: absolute;bottom: 10px;right: 10px;" id="badge" src="' . esc_url($img) . '">';
    echo $html;
}
add_action('blog_badge_output', 'call_badge_blog_index');
