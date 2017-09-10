<?php get_header(); ?>
<?php inlar_header('inlar_news', 'hero'); ?>
<main id="content" class="container">
	<div class="entry-news"><?php
		if (have_posts()) : while (have_posts()) : the_post(); 
			include(locate_template('partials/loop-news.php'));
		endwhile; endif;
	?></div>
	<?php inlar_paginate(); ?>
</main>
<?php get_footer(); ?>
