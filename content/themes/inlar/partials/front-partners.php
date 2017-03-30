<section class="partners-container">
	<?php inlar_header('inlar_partners', 'section'); ?>
	<div class="entry-partners"><?php
			$partners = new WP_Query(array(
				'post_type'      => 'partner',
				'posts_per_page' => 4,
				'orderby'        => 'post_date', // TODO: maybe custom orderby?
			));
			
			if ($partners->have_posts()) : while ($partners->have_posts()) : $partners->the_post(); ?>
				<div class="partner">
					<div class="partner-box">
						<?php
							if (has_post_thumbnail()) {
								the_post_thumbnail('partner-logo-small');
							}
						?>
						<h2 class="partner-name"><?php the_title(); ?></h2>
					</div>
				</div>
			<?php endwhile; endif;
		?></div>
</div>
</section>

