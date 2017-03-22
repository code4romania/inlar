<?php
	/**
	 * [inlar_country_buttons description]
	 *
	 * TODO: add dropdown when showing more countries than $show_front
	 */
	function inlar_country_buttons() {
		$show_front = 3;

		$countries = apply_filters('ptf_get_countries', null);

		foreach ($countries as $slug => $country) {
			printf('<a href="%1$s" class="button"><img src="%3$s/assets/images/flags/%4$s.png" alt="%2$s" class="flag png2svg"> %2$s</a>',
				get_term_link($country['id']), $country['name'],
				get_template_directory_uri(), $country['flag']
			);
		}
	}
?>
