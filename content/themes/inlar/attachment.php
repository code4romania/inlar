<?php
	// We're not using attachment pages
	wp_safe_redirect(wp_get_attachment_url($post->ID), 301);
?>
