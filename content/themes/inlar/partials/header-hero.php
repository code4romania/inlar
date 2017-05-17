<?php
	global $post;

	$prefix = 'no';
	$image = '';

	if (get_post_type($post) == 'page' && has_post_thumbnail($post)) {
		$image = sprintf('<div class="header-image" style="background-image:url(\'%s\')"></div>',
			get_the_post_thumbnail_url($post, 'full')
		);
		$prefix = 'has';
	} else if (has_header_image()) {
		$image = sprintf('<div class="header-image" style="background-image:url(\'%s\')"></div>',
			get_header_image()
		);
		$prefix = 'has';
	}
?>
<div class="hero <?php echo $prefix; ?>-header-image">
	<?php echo $image; ?>
	<div class="container narrow header-adjust">
		<h1><?php echo $title; ?></h1>
		<p><?php echo $text; ?></p>
	</div>
</div>
