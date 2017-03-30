<?php
	$prefix = $image = '';

	if (has_header_image()) {
		$image = sprintf('<div class="header-image" style="background-image:url(\'%s\')"></div>',
			get_header_image()
		);
		$prefix = 'has';
	} else {
		$prefix = 'no';
	}
?>
<div class="hero <?php echo $prefix; ?>-header-image">
	<?php echo $image; ?>
	<div class="container">
		<h1><?php echo $title; ?></h1>
		<p><?php echo $text; ?></p>
	</div>
</div>
