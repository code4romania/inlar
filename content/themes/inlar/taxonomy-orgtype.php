<?php get_header(); ?>
<?php inlar_header_raw(single_term_title('', false), '', 'hero'); ?>
<main id="content" class="container entry-partners"><?php
	if (have_posts()) : while (have_posts()) : the_post();
		include(locate_template('partials/loop-member.php'));
	endwhile; inlar_paginate(); endif;
?></main>
<?php get_footer(); ?>
