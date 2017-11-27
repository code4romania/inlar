<?php
/**
 * Post Type Framework Taxonomy: Organization Type
 */

class Post_Type_Taxonomy_Org_Type {
	protected $tax_name;
	protected $tax_args;

	function __construct() {
		$this->setup_post_tax();

		add_action('init'                               , array($this, 'register_post_tax'), 5);

		// Taxonomy
		add_action("{$this->tax_name}_add_form_fields"  , array($this, 'add_term_fields'));
		add_action("{$this->tax_name}_edit_form_fields" , array($this, 'edit_term_fields'));

		add_action("edited_{$this->tax_name}"           , array($this, 'save_term_fields'), 10, 2);
		add_action("create_{$this->tax_name}"           , array($this, 'save_term_fields'), 10, 2);
	}

	function setup_post_tax() {
		$this->tax_name = 'orgtype';
		$this->tax_args = array(
			'labels'			=> array(
				'name'				=> __('Types', 'ptf'),
				'singular_name'		=> __('Type', 'ptf'),
				'search_items'		=> __('Search Types', 'ptf'),
				'all_items'			=> __('All Types', 'ptf'),
				'edit_item'			=> __('Edit Type', 'ptf'),
				'update_item'		=> __('Update Type', 'ptf'),
				'add_new_item'		=> __('Add new Type', 'ptf'),
				'new_item_name'		=> __('New Type', 'ptf'),
				'not_found'			=> __('No types found.', 'ptf'),
				'menu_name'			=> __('Types', 'ptf')
			),
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'show_in_menu'		=> true,
			'show_in_nav_menus'	=> false,
			'show_tagcloud'		=> false,
			'show_admin_column'	=> true,
			'public'			=> true,
			'rewrite'			=> array(
				'slug'			=> $this->tax_name,
				'with_front' 	=> false,
			),
		);
	}

	function register_post_tax() {
		register_taxonomy($this->tax_name, array('ngo'), $this->tax_args);
	}

	function term_fields() {
		return [
			'icon' => [
				'label'     => __('Icon URL', 'ptf'),
				'type'      => 'url',
				'default'   => '',
			],
		];
	}

	function add_term_fields($taxonomy) {
		$fields = $this->term_fields();

		foreach ($fields as $key => $conf) {
			printf('<div class="form-field term-%1$s-wrap"><label for="%1$s">%2$s</label><input type="%3$s" id="%1$s" name="%1$s" value="%4$s"></div>',
				$key, $conf['label'], $conf['type'], $conf['default']
			);
		}
	}

	function edit_term_fields($term) {
		$fields = $this->term_fields();

		$row = '<tr class="form-field">';
		$row.= '<th scope="row" valign="top"><label for="%s">%s</label></th>';
		$row.= '<td><input type="%3$s" id="%1$s" name="%1$s" value="%4$s"></td>';
		$row.= '</tr>';

		foreach ($fields as $key => $conf) {
			$value = get_term_meta($term->term_id, "{$this->tax_name}_{$key}", true);

			printf($row,
				$key, $conf['label'], $conf['type'], $value
			);
		}
	}

	function save_term_fields($term_id) {
		$fields = $this->term_fields();
		$defaults = array_map(function($n) { return $n['default']; }, $fields);

		foreach ($fields as $key => $conf) {
			if (!isset($_POST[ $key ]))
				continue;

			switch ($conf['type']) {
				case 'url':
					$value = ($_POST[ $key ] ? esc_url_raw( $_POST[ $key ] ) : $defaults[ $key ]);
					break;
				
				default:
					$value = ($_POST[ $key ] ? sanitize_key( $_POST[ $key ] ) : $defaults[ $key ]);
					break;
			}

			update_term_meta($term_id, "{$this->tax_name}_{$key}", $value);
		}
	}
}

new Post_Type_Taxonomy_Org_Type;

?>
