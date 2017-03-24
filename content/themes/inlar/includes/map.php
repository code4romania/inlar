<?php
/**
 * [inlar_country_buttons description]
 *
 * TODO: add dropdown when showing more countries than $show_front
 */
function inlar_country_buttons() {
	$show_front = 3;

	$countries = apply_filters('ptf_get_countries', null);

	foreach ($countries as $country) {
		printf('<a href="%1$s" data-country="%2$s" class="button"><img src="%4$s/assets/images/flags/%5$s.png" alt="%3$s" class="flag png2svg"><span class="name">%3$s</span></a>',
			get_term_link($country['id']), $country['id'], $country['name'],
			get_template_directory_uri(), $country['flag']
		);
	}
}

function inlar_country_map_select($current = 0) {
	$countries = apply_filters('ptf_get_countries', null);

	$current = $dropdown = '';

	foreach ($countries as $country) {
		if ($country['id'] === $current) {
			$current = sprintf('<span class="button current"><img src="%2$s/assets/images/flags/%3$s.png" alt="%1$s" class="flag png2svg">%1$s</span>',
				$country['name'], get_template_directory_uri(), $country['flag']
			);
		} else {
			$dropdown.= sprintf('<li><a href="%1$s" data-country="%2$s" class="button">%3$s</a></li>',
				get_term_link($country['id']), $country['id'], $country['name']
			);
		}
	}

	$dropdown = sprintf('<div class="dropdown"><button class="button dropdown-toggle">%s</button><ul class="dropdown-menu">%s</ul></div>',
		__('Another country', 'inlar'), $dropdown
	);


	printf('<div class="country-control">%s%s</div>',
		$current, $dropdown
	);
}
?>
