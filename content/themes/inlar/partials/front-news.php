<section id="news" class="news-container">
	<div class="container">
		<?php inlar_header('inlar_news', 'section'); ?>
		<div class="entry-news"><?php
			$news = new WP_Query(array(
				'post_type'      => 'post',
				'posts_per_page' => 4,
			));

			if ($news->have_posts()) : while ($news->have_posts()) : $news->the_post();
				include(locate_template('partials/loop-news.php'));
			endwhile; endif;
		?></div>
		<div class="button-container"><?php
			printf('<a href="%s" class="button button-main">%s<i class="icon-arrow-green"></i><i class="icon-arrow"></i></a>',
				get_permalink(get_option('page_for_posts')), __('See more news', 'inlar')
			);
		?></div>
	</div>
</section>

