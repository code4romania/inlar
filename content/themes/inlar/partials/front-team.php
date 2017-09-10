<section id="team" class="team-container">
	<div class="container">
		<?php inlar_header('inlar_team', 'section'); ?>
		<div class="entry-team"><?php
			$members = new WP_Query(array(
				'post_type'      => 'team',
				'posts_per_page' => -1,
			));

			if ($members->have_posts()) : while ($members->have_posts()) : $members->the_post(); ?>
				<article class="member" itemscope itemprop="member" itemtype="http://schema.org/Person">
					<?php
						if (has_post_thumbnail()) {
							the_post_thumbnail('team-profile', array(
								'itemprop' => 'image'
							));
						}

						printf('<h1 class="member-name" itemprop="name">%s</h1>',
							get_the_title()
						);

						$title = get_post_meta($post->ID, '_ptf_team_title_meta', true);
						if (!empty($title)) {
							printf('<div class="member-title" itemprop="jobTitle">%s</div>',
								$title
							);
						}
					?>
				</article>
			<?php endwhile; endif;
		?></div>
		<div class="button-container"><?php
			printf('<a href="%s" class="button button-main">%s<i class="icon-arrow-green"></i><i class="icon-arrow"></i></a>',
				inlar_get_page_archive_link('team'), __('See full team', 'inlar')
			);
		?></div>
	</div>
</section>

