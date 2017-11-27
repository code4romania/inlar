<?php
	$orgtypes = get_terms( array(
		'taxonomy'   => 'orgtype',
		'hide_empty' => true,
	));

	if (!$orgtypes)
		return;
?>
<section id="members" class="partners-container">
	<div class="container">
		<?php inlar_header_raw(__('Members', 'inlar'), '', 'section'); ?>
		<div class="entry-news"><?php
			foreach ($orgtypes as $orgtype): ?>
				<article class="entry" itemscope itemprop="member">
					<a href="<?php echo get_term_link($orgtype); ?>"><?php
						$icon = get_term_meta($orgtype->term_id, 'orgtype_icon', true);
						if ($icon) {
							printf('<img src="%s" alt="" itemprop="image">',
								esc_attr($icon)
							);
						}

						printf('<div class="entry-meta"><h1 class="entry-title" itemprop="name">%s</h1></div>',
							__($orgtype->name)
						);
					?></a>
				</article>
			<?php endforeach;
		?></div>
	</div>
</section>

