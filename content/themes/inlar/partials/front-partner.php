<?php
	$opt = get_option('inlar_partners');

	$featured = (isset($opt['featured']) && !empty($opt['featured'])) ? $opt['featured'] : array();

	if (empty($featured))
		return;
?>
<section id="partners" class="partners-container">
	<div class="container">
		<?php inlar_header('inlar_partners', 'section'); ?>
		<div class="entry-partners"><?php
			$partners = new WP_Query(array(
				'post_type'      => 'partner',
				'posts_per_page' => count($featured),
				'post__in'       => $featured,
				'orderby'        => 'post__in',
			));

			if ($partners->have_posts()) : while ($partners->have_posts()) : $partners->the_post();
				$logo = has_post_thumbnail() ? get_the_post_thumbnail($post, 'partner-logo-small') : ''; 
				$url  = get_post_meta($post->ID, '_ptf_partner_url_meta', true);

				if ($url) {
					$before = sprintf('<a href="%s" target="_blank" class="entry">', $url);
					$after  = '</a>';
				} else {
					$before = '<div class="entry">';
					$after  = '</div>';
				}

				printf('%s%s<h2 class="entry-title">%s</h2>%s',
					$before, $logo, get_the_title($post), $after
				);
			endwhile; endif;
		?></div>
		<div class="button-container"><?php
			printf('<a href="%s" class="button button-main">%s<i class="icon-arrow-green"></i><i class="icon-arrow"></i></a>',
				inlar_get_page_archive_link('partner'), __('See all partners', 'inlar')
			);
		?></div>
	</div>
</section>

