<?php get_header(); ?>
<?php inlar_header_raw(get_the_title(), '', 'hero'); ?>
<main id="content" class="container">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article id="page-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/WebPage">
			<h1 class="entry-title screen-reader-text"><?php the_title(); ?></h1>
			<div class="entry-content"><?php the_content(); ?></div>
		</article>
	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
