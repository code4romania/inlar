<?php get_header(); ?>
<?php get_template_part('partials/front', 'map'); ?>
<?php get_template_part('partials/front', 'ngo'); ?>
<main id="content" class="front-sections">
	<?php get_template_part('partials/front', 'news'); ?>
	<?php get_template_part('partials/front', 'contact'); ?>
	<?php get_template_part('partials/front', 'team'); ?>
	<?php get_template_part('partials/front', 'partner'); ?>
</main>
<?php get_footer(); ?>
