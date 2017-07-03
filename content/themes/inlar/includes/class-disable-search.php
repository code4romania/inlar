<?php

class INLAR_Disable_Search {

	function __construct() {
		add_action('admin_bar_menu',  array($this, 'remove_admin_bar_menu'));
		add_action('widgets_init',    array($this, 'remove_search_widget'));
		add_action('parse_query',     array($this, 'clear_query'));
	}

	function remove_admin_bar_menu($wp_admin_bar) {
		$wp_admin_bar->remove_menu('search');
	}

	function remove_search_widget() {
		unregister_widget('WP_Widget_Search');
	}

	function clear_query($query) {
		if (is_admin())
			return;

		if (!$query->is_search || !$query->is_main_query())
			return;

		unset($_GET['s'], $_POST['s'], $_REQUEST['s'], $query->query['s']);

		$query->set('s', '');
		$query->is_search = false;
		$query->set_404();
		status_header(404);
	}
}


new INLAR_Disable_Search();

?>
