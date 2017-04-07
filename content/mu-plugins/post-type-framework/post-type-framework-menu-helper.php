<?php
/**
 * Adds custom post type archives to the WordPress-powered menus
 */

class Post_Type_Framework_Menu_Helper {
	function __construct() {
		add_action('admin_head-nav-menus.php', array($this, 'add_meta_box'), 10, 1);
		add_filter('wp_get_nav_menu_items',    array($this, 'filter_menu'), 10, 3);
	}

	function add_meta_box() {
		add_meta_box(
			'ptf-nav',
			__('Archive links', 'ptf'),
			array($this, 'render_meta_box'),
			'nav-menus',
			'side',
			'default'
		);
	}

	function render_meta_box() {
		global $nav_menu_selected_id;

		// Get all custom post types with archive support
		$post_types = get_post_types(array(
			'show_in_nav_menus' => true,
			'has_archive'       => true,
		), 'object');

		// Populate the necessary properties for walker identification
		foreach ($post_types as &$post_type) {
			$post_type->classes          = array();
			$post_type->type             = $post_type->name;
			$post_type->object_id        = $post_type->name;
			$post_type->title            = $post_type->labels->name;
			$post_type->object           = 'ptf-nav';

			$post_type->menu_item_parent = false;
			$post_type->url              = false;
			$post_type->xfn              = false;
			$post_type->db_id            = false;
			$post_type->target           = false;
			$post_type->attr_title       = false;
		}

		// Use the native menu walker
		$w = new stdClass();
		$w->walker = new Walker_Nav_Menu_Checklist(array());

		?>
		<div id="ptf-nav" class="posttypediv">
			<div id="tabs-panel-ptf-nav" class="tabs-panel tabs-panel-active">
				<?php
					printf('<ul id="ctp-archive-checklist" class="categorychecklist form-no-clear">%s</ul>',
						walk_nav_menu_tree(array_map('wp_setup_nav_menu_item', $post_types), 0, $w)
					);
				?>
			</div><!-- /.tabs-panel -->
			</div>
			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit"<?php disabled($nav_menu_selected_id, 0); ?> class="button-secondary submit-add-to-menu" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-ctp-archive-menu-item" id="submit-ptf-nav" />
					<span class="spinner"></span>
				</span>
			</p>
		<?php
	}

	function filter_menu($items, $menu, $args) {
		// Alter the URL for ptf-nav objects
		foreach ($items as &$item) {
			if ($item->object !== 'ptf-nav')
				continue;
		  
			$item->url = get_post_type_archive_link($item->type);

			if (get_query_var('post_type') == $item->type) {
				$item->classes[] = 'current-menu-item';
				$item->current = true;
			}
		}

		return $items;
	}
}

new Post_Type_Framework_Menu_Helper;
?>
