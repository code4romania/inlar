<?php

function inlar_navigation_menu($location) {
	// Exit if we don't have a custom menu
	if (!has_nav_menu($location))
		return;

	wp_nav_menu(array(
		'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'theme_location' => $location,
		'menu_class'     => 'menu flex-item',
		'echo'           => true,
		'container'      => '',
		'depth'          => 1,
	));
}
?>
