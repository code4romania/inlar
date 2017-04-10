<?php get_header(); ?>
<?php inlar_header('inlar_partners', 'hero'); ?>
<main id="content" class="container entry-partners">
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<div class="partner" itemscope="itemscope" itemtype="http://schema.org/Organization">
			<div class="partner-box">
				<?php
					if (has_post_thumbnail()) {
						the_post_thumbnail('partner-logo-large', array(
							'itemprop' => 'logo'
						));
					}
				?>
				<div class="partner-content">
					<h2 class="partner-name" itemprop="name"><?php the_title(); ?></h2><?php
					// empty php block to strip white-space in display: inline-block cases
					?><div class="partner-desc"><?php
						the_content();

						$partner_url = get_post_meta($post->ID, '_ptf_partner_url_meta', true);

						if (!empty($partner_url)) {
							printf('<a href="%s" class="button partner-button" itemprop="url">%s<i class="icon-arrow"></i></a>',
								$partner_url, __('Visit website', 'inlar')
							);
						}
					?></div>
				</div>
			</div>
		</div>
	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
