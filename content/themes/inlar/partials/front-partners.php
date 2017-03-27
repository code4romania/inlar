<section class="partners-container">
	<header class="entry-header">
		<?php
			// TODO: replace with proper option
		?>
		<h1 class="entry-title">Our Partners</h1>
		<p>NGOs which share our values and can work with us on various future projects</p>
	</header>
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
						// TODO: replace with featured image
						?>
						<img src="//lorempixel.com/150/150/abstract/" alt="">
						<h2 class="partner-name"><?php the_title(); ?></h2>
					</div>
				</div>
			<?php endwhile; endif;
		?></div>
</div>
</section>

