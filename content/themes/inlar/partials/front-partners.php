<?php
	$opt = get_option('inlar_partners');

	$featured = (isset($opt['featured']) && !empty($opt['featured'])) ? $opt['featured'] : array();

	if (empty($featured))
		return;
?>
<section class="partners-container">
	<?php inlar_header('inlar_partners', 'section'); ?>
	<div class="entry-partners"><?php
		$partners = new WP_Query(array(
			'post_type'      => 'partner',
			'posts_per_page' => count($featured),
			'post__in'       => $featured,
			'orderby'        => 'post__in',
		));

		if ($partners->have_posts()) : while ($partners->have_posts()) : $partners->the_post(); ?>
			<div class="partner">
				<a href="<?php the_permalink(); ?>" class="partner-box">
					<?php
						if (has_post_thumbnail()) {
							the_post_thumbnail('partner-logo-small');
						}
					?>
					<h2 class="partner-name"><?php the_title(); ?></h2>
				</a>
			</div>
		<?php endwhile; endif;
	?></div>
</div>
</section>

