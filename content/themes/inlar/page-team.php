<?php
/**
 * Template Name: Team
 */
?>
<?php get_header(); ?>
<?php inlar_header('inlar_team', 'hero'); ?>
<main id="content" class="container"><?php
	$page_id = get_the_ID();

	$team = new WP_Query(array(
		'post_type'      => 'team',
		'posts_per_page' => -1,
	));

	print ('<div class="entry-team">');
	if ($team->have_posts()) : while ($team->have_posts()) : $team->the_post();
		include(locate_template('partials/loop-team.php'));
	endwhile; endif;
	print ('</div>');
?></main>
<?php get_footer(); ?>
