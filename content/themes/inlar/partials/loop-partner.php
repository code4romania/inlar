<div class="entry" itemscope="itemscope" itemtype="http://schema.org/Organization">
	<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail('partner-logo-large', array(
				'itemprop' => 'logo'
			));
		}
	?>
	<div class="entry-content">
		<h2 class="entry-title" itemprop="name"><?php the_title(); ?></h2><?php
		// empty php block to strip white-space in display: inline-block cases
		?><div class="entry-desc"><?php
			the_content();

			$partner_url = get_post_meta($post->ID, '_ptf_partner_url_meta', true);

			if (!empty($partner_url)) {
				printf('<a href="%s" class="button button-main" itemprop="url">%s<i class="icon-arrow-green"></i><i class="icon-arrow"></i></a>',
					$partner_url, __('Visit website', 'inlar')
				);
			}
		?></div>
	</div>
</div>
