<?php
	global $post;

	$prefix = 'no';
	$image = '';

	if (is_home()) {
		$image = sprintf('<div class="header-image" style="background-image:url(\'%s\')"></div>',
			get_the_post_thumbnail_url(get_option('page_for_posts'), 'full')
		);
		$prefix = 'has';
	} else if (in_array(get_post_type($post), array('post', 'page')) && has_post_thumbnail($post)) {
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
	<div class="container narrow header-adjust"><?php
		if ($title)
			printf('<h1>%s</h1>', $title);

		if ($text)
			printf('<p>%s</p>', $text);
	?></div>
</div>
