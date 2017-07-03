<?php $opt = get_option('inlar_contact'); ?>
<section class="contact-container">
	<div class="container">
		<?php inlar_header('inlar_contact', 'section'); ?>
		<div class="entry-contact">
			<div class="form-container"><?php
				do_action('inlar_contact_form_display');
				inlar_fetch_template('form_response');
			?></div>
			<ul class="details"><?php
				if (!empty($opt['email'])) {
					printf('<li><strong>%s</strong><a href="mailto:%s">%s</a></li>',
						__('Email address', 'inlar'),
						esc_attr($opt['email']),
						$opt['email']
					);
				}

				if (!empty($opt['phone'])) {
					printf('<li><strong>%s</strong><a href="tel:%s">%s</a></li>',
						__('Phone', 'inlar'),
						esc_attr(preg_replace('/[^0-9+]/', '', $opt['phone'])),
						$opt['phone']
					);
				}

				if (!empty($opt['address'])) {
					printf('<li><strong>%s</strong><address>%s</address></li>',
						__('Address', 'inlar'),
						$opt['address']
					);
				}
				
			?></ul>
			<div class="social"><?php
				printf('<strong>%s</strong>',
					__('Social media', 'inlar')
				);

				$media = array('facebook', 'twitter', 'instagram', 'medium');

				$tpl = '<li><a href="%1$s" target="_blank">';
				$tpl.= '<span class="icon"><i class="icon-%2$s"></i><i class="icon-%2$s-green alt"></i></span>';
				$tpl.= '%3$s';
				$tpl.= '</a></li>';

				$links = array();

				foreach ($media as $medium) {
					if (!empty($opt[$medium])) {
						$links[] = sprintf($tpl,
							$opt[$medium], $medium, ucfirst($medium)
						);
					}
				}

				if (!empty($links)) {
					printf('<ul>%s</ul>',
						implode('', $links)
					);
				}
			?></div>
		</div>
	</div>
</section>

