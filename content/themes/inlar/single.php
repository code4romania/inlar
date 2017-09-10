<?php get_header(); ?>
<?php inlar_header_raw(get_the_title(), get_the_date(), 'hero'); ?>
<main id="content" class="container">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<article id="page-<?php the_ID(); ?>" <?php post_class(); ?> itemscope="itemscope" itemtype="http://schema.org/WebPage">
			<header class="hidden"><?php
				// Just for the sake of the html structure. We're already showing this info in the hero.
				printf('<div class="entry-meta"><h1 class="entry-title" itemprop="headline">%s</h1><time itemprop="datePublished" datetime="%s">%s</time></div>',
					get_the_title(), get_the_date('c'), get_the_date()
				);
			?></header>
			<div class="entry-content"><?php the_content(); ?></div>
		</article>
	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
