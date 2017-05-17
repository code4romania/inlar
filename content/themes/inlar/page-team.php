<?php
/**
 * Template Name: Team
 */
?>
<?php get_header(); ?>
<?php inlar_header('inlar_team', 'hero'); ?>
<main id="content" class="container entry-team"><?php
	$team = new WP_Query(array(
		'post_type'      => 'team',
		'posts_per_page' => -1,
	));

	if ($team->have_posts()) : while ($team->have_posts()) : $team->the_post();
		include(locate_template('partials/loop-team.php'));
	endwhile; endif;
?></main>
<?php get_footer(); ?>
