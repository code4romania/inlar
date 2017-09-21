<?php

/**
 * Generates the initial country selections buttons.
 *
 * TODO: fix buttons overflow for intermediary screen sizes
 */
function inlar_country_buttons() {
	$show_front = 2;

	$countries = apply_filters('ptf_get_countries', null);
	$main_list = $drop_list = $select_list = '';

	$select_list = sprintf('<option value="0">%s</option>',
		__('Select a country', 'inlar')
	);

	if (!$countries)
		return;

	foreach ($countries as $index => $country) {
		if ($index < $show_front) {
			$main_list.= sprintf('<li class="button" data-country="%1$s"><img src="%3$s" alt="" class="flag"><span class="name">%2$s</span></li>',
				$country['id'], $country['name'], $country['flag']
			);
		} else {
			$drop_list.= sprintf('<li data-country="%1$s">%2$s</li>',
				$country['id'], $country['name']
			);
		}

		$select_list.= sprintf('<option value="%d">%s</option>',
			$country['id'], $country['name']
		);
	}

	if (!empty($drop_list)) {
		$dropdown = '<li class="button dropdown-container">';
		$dropdown.= '<div class="dropdown-toggle">%s<i class="icon-arrow"></i></div>';
		$dropdown.= '<ul class="dropdown top-right">%s</ul></li>';

		$drop_list = sprintf($dropdown,
			__('More countries', 'inlar'),
			$drop_list
		);
	}

	$select_list = sprintf('<li class="button mobile-select">%s<select>%s</select></li>',
		__('Select a country', 'inlar'), $select_list
	);

	printf('<ul class="countries">%s%s%s</ul>',
		$main_list, $drop_list, $select_list
	);
}

?>
