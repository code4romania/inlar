<?php 
/**
 * In lieu of an actual single page template
 */

$partner_url = get_post_meta($post->ID, '_ptf_partner_url_meta', true);

if (!empty($partner_url)) {
	// If the partner has a specified URL, redirect to it
	wp_redirect($partner_url);
} else {
	// If there's no URL, redirect back to the partners archive page
	wp_safe_redirect(get_post_type_archive_link($post->post_type), 302);
}

exit;

?>
