<div class="entry" itemscope="itemscope" itemtype="http://schema.org/Organization">
	<?php
		if (has_post_thumbnail()) {
			the_post_thumbnail('partner-logo-large', array(
				'itemprop' => 'logo'
			));
		}
	?>
	<div class="entry-content">
		<?php
			$country = get_the_terms($post, 'country')[0];
			if ($country) {
				printf('<div class="country"><span class="name">%s</span><img src="%s/assets/images/flags/%s.png" alt="" class="flag"></div>',
					__($country->name),
					get_template_directory_uri(),
					get_term_meta($country->term_id, '_ptf_flag_id', true)
				);
			}
		?>
		<h2 class="entry-title" itemprop="name"><?php the_title(); ?></h2>
		<div class="entry-desc">
			<?php the_content(); ?>
			<ul class="ngo-meta"><?php

				$phone = get_post_meta($post->ID, '_ptf_ngo_phone_meta', true);
				if ($phone) {
					printf('<li><i class="icon-%1$s"></i><span class="%1$s">%2$s</span></li>',
						'phone', $phone
					);
				}

				$email = get_post_meta($post->ID, '_ptf_ngo_email_meta', true);
				if ($email) {
					printf('<li><i class="icon-%1$s"></i><a href="mailto:%2$s" class="%1$s">%3$s</a></li>',
						'email', esc_attr($email), $email
					);
				}

				$url = get_post_meta($post->ID, '_ptf_ngo_url_meta', true);
				if ($url) {
					printf('<li><i class="icon-%1$s"></i><a target="_blank" href="%3$s" class="%2$s">%4$s</a></li>',
						'url', 'link', esc_attr($url), $url
					);
				}

				$address = get_post_meta($post->ID, '_ptf_ngo_address_meta', true);
				if ($address) {
					printf('<li><i class="icon-%1$s"></i><address>%2$s</address></li>',
						'map', $address
					);
				}
			?></ul>
		</div>
	</div>
</div>
