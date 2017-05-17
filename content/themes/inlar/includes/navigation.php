<?php

function inlar_navigation_menu($location, $class = 'menu') {
	// Exit if we don't have a custom menu
	if (!has_nav_menu($location))
		return;

	wp_nav_menu(array(
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'theme_location' => $location,
		'echo'           => true,
		'menu_class'     => $class,
		'container'      => '',
		'depth'          => 1,
	));
}

function inlar_navigation_language_switcher() {
	global $q_config;

	if (!function_exists('qtranxf_getSortedLanguages'))
		return;

	$languages = qtranxf_getSortedLanguages();

	$active = '';
	$output = array();

	foreach ($languages as $language) {
		if ($language === $q_config['language']) {
			$active = sprintf('<span class="current dropdown-toggle">%s <i class="icon-arrow"></i></span>',
				$language
			);
		} else {
			$output[] = sprintf('<li><a href="%1$s" hreflang="%2$s">%2$s</a></li>',
				qtranxf_convertURL(is_404() ? home_url() : '', $language),
				$q_config['language_name'][$language]
			);
		}
	}

	printf('<div class="lang-switcher dropdown-container">%s<ul class="dropdown top-right">%s</ul></div>',
		$active, implode('', $output)
	);
}

function inlar_navigation_mobile($location) {
	global $q_config;

	$menu = $active = '';

	if (has_nav_menu($location)) {
		$menu = wp_nav_menu(array(
			'items_wrap'     => '%3$s',
			'theme_location' => $location,
			'echo'           => false,
			'container'      => '',
			'depth'          => 1,
		));
	}

	if (function_exists('qtranxf_getSortedLanguages')) {
		$languages = qtranxf_getSortedLanguages();
		$langs = array();

		foreach ($languages as $language) {
			$langs[] = sprintf('<option value="%1$s"%3$s>%2$s</option>',
				qtranxf_convertURL(is_404() ? home_url() : '', $language),
				$q_config['language_name'][$language],
				selected($language, $q_config['language'], false)
			);
		}
	}

	print ('<div class="dropdown-container">');
	print ('<button class="icon-menu dropdown-toggle"><hr class="bar"><hr class="bar"><hr class="bar"></button>');

	printf('<ul id="%1$s-mobile" class="%2$s">%3$s',
		$location, 'dropdown top-right', $menu
	);

	printf('<li class="lang-container"><label for="%1$s">%2$s</label><i class="icon-select"></i><select id="%1$s">%3$s</select></li>',
		'lang-select',
		__('Language', 'inlar'),
		implode('', $langs)
	);

	print ('</ul></div>');
}

?>
