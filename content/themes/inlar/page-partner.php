<?php
/**
 * Template Name: Partners
 */
?>
<?php get_header(); ?>
<?php inlar_header('inlar_partners', 'hero'); ?>
<main id="content" class="container entry-partners"><?php
	$partners = new WP_Query(array(
		'post_type'      => 'partner',
		'posts_per_page' => -1,
	));

	if ($partners->have_posts()) : while ($partners->have_posts()) : $partners->the_post();
		include(locate_template('partials/loop-partner.php'));
	endwhile; endif;
?></main>
<?php get_footer(); ?>
