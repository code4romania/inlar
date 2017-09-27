<?php
	$members_page = (int) get_option('inlar_partners')['members'];

	$subpages = new WP_Query(array(
		'post_type'      => 'page',
		'post_parent'    => $members_page,
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	));

	if (!$subpages->have_posts())
		return;
?>
<section id="members" class="partners-container">
	<div class="container">
		<?php inlar_header_raw(__('Members', 'inlar'), '', 'section'); ?>
		<div class="entry-news"><?php
			while ($subpages->have_posts()) : $subpages->the_post(); ?>
				<article class="entry" itemscope itemprop="member">
					<a href="<?php the_permalink(); ?>"><?php
						if (has_post_thumbnail()) {
							the_post_thumbnail('medium', array(
								'itemprop' => 'image'
							));
						}

						printf('<div class="entry-meta"><h1 class="entry-title" itemprop="name">%s</h1></div>',
							get_the_title()
						);
					?></a>
				</article>
			<?php endwhile;
		?></div>
	</div>
</section>

