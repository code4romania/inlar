<?php
/**
 * Post Type Framework: Partner
 */

class Post_Type_Partner extends Post_Type_Framework {
	protected $post_type_name;
	protected $post_type_args;

	function __construct() {
		parent::__construct();

		$this->setup();

		// Hook into the actual registration process
		add_action('init', array($this, 'register_post_type'));
		add_action('add_meta_boxes_'.$this->post_type_name, array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save_meta_box_values'));

		// Register custom post types into the dashboard glance widget
		add_filter('dashboard_glance_items', array($this, 'dashboard_glance_items'));
	}

	/**
	 * Load config values into class object
	 */
	function setup() {
		// Setup custom post types
		$this->post_type_name	= 'partner';
		$this->post_type_args	= array(
			'labels'				=> array(
				'name'					=> __('Partners', 'ptf'),
				'singular_name'			=> __('Partner', 'ptf'),
				'add_new'				=> __('Add New', 'ptf'),
				'add_new_item'			=> __('Add Partner', 'ptf'),
				'edit_item'				=> __('Edit Partner', 'ptf'),
				'all_items'				=> __('All Partners', 'ptf'),
				'view_item'				=> __('View Partner', 'ptf'),
				'search_items'			=> __('Search Partners', 'ptf'),
				'not_found'				=> __('No partners found', 'ptf'),
				'not_found_in_trash'	=> __('No partners found in Trash', 'ptf'),
				'menu_name'				=> __('Partners', 'ptf')
			),
			'menu_icon'				=> 'dashicons-groups',
			'hierarchical'			=> false,
			'supports'				=> array('title', 'editor', 'excerpt', 'thumbnail'),
			'public'				=> true,
			'show_ui'				=> true,
			'menu_position'			=> 20,
			'show_in_nav_menus'		=> true,
			'publicly_queryable'	=> true,
			'exclude_from_search'	=> false,
			'has_archive'			=> true,
			'query_var'				=> true,
			'can_export'			=> true,
			'rewrite'				=> array('slug' => $this->post_type_name, 'with_front' => false),
			'capability_type'		=> 'post'
		);
	} 

	/**
	 * Let parent class handle post type registration
	 */
	function register_post_type() {
		parent::register_post_type();
	}

	/**
	 * Add a custom meta box
	 */
	function add_meta_box() {
		$fields = $this->get_meta_box_fields();
		if (empty($fields))
			return;

		add_meta_box(
			'ptf_project',
			__('Additional Information', 'ptf'),
			array($this, 'render_meta_box'),
			$this->post_type_name,
			'advanced',
			'high'
		);
	}

	function get_meta_box_fields() {
		return array(
		);
	}

	function get_default_values() {
		return array(
		);
	}

	/**
	 * Fetch and parse meta box values
	 * @return	array	Array of meta box values
	 */
	function get_meta_box_values($post_id = 0) {
		global $post;

		if (!$post_id)
			$post_id = $post->ID;

		$defaults = $this->get_default_values();
		$values = array();

		foreach ($defaults as $k => $v) {
			$values[$k] = get_post_meta($post_id, "_ptf_{$this->post_type_name}_{$k}_meta", true);
		}

		return wp_parse_args($values, $defaults);
	}

	/**
	 * Prints the box content.
	 * 
	 * @param	WP_Post		$post	The object for the current post/page.
	 */
	function render_meta_box($post, $metabox) {
		$values = $this->get_meta_box_values();
		$fields = $this->get_meta_box_fields();

		// Add an nonce field so we can check for it later.
		wp_nonce_field('ptfp', 'ptfp_nonce');


		print('<table class="form-table cmb_metabox">');
		foreach ($fields as $name => $options) {
			parent::render_meta_box_field($name, $options, $values[$name]);
		}
		print('</table>');
	}

	/**
	 * Save custom data on post save.
	 * 
	 * @param	int		$post_id	The ID of the post being saved.
	 */
	function save_meta_box_values($post_id, $values = array()) {

		// Verify that the nonce is set and is valid.
		if (!isset($_POST['ptfp_nonce']) || !wp_verify_nonce($_POST['ptfp_nonce'], 'ptfp'))
			return;

		// If this is an autosave, the form wasn't submitted,
		// so we don't want to do anything.
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return;

		// Check that we're only processing the metabox
		// registered for this custom post type
		if (empty($_POST['post_type']) || $_POST['post_type'] !== $this->post_type_name)
			return;

		// Check the user's permissions.
		if (!current_user_can('edit_post', $post_id))
			return;

		$values = $_POST['ptf'][$this->post_type_name];

		// Force int values
		foreach ($values as $k => $v) {
			update_post_meta($post_id, "_ptf_{$this->post_type_name}_{$k}_meta", absint($v));
		}

		// parent::save_meta_box_values($post_id, $values);
	}

	function get_countries_conf() {
		return array(
			'name'      => $this->post_type_taxonomy_name . '_flag',
			'term'		=> '_ptf_flag_id',
			'label'     => __('Flag', 'ptf'),
			'countries' => apply_filters('countries_helper_get_list', null),
			'url'		=> sprintf('%s/assets/images/flags/%%s.png',
				get_template_directory_uri()
			)
		);
	}

	function add_term_fields($taxonomy) {
		$conf = $this->get_countries_conf();

		if (empty($conf['countries']))
			return;

		printf('<div class="form-field term-country-wrap">'.
			'<label for="%1$s">%2$s</label>'.
			'<select name="%1$s" id="%1$s">',
			$conf['name'], $conf['label']
		);

		printf('<option value="">%s</option>', __('-- Select --', 'ptf'));
		foreach ($conf['countries'] as $iso => $name) {
			printf('<option value="%1$s">%2$s</option>',
				$iso, $name
			);
		}

		print ('</select>');

		printf('<img src="" alt="%s" id="flag_preview">',
			$conf['label']
		);

		print ('</div>');
	}

	function edit_term_fields($term) {
		$conf = $this->get_countries_conf();
		$flag = get_term_meta($term->term_id, $conf['term'], true);

		print ('<tr class="form-field">');
		printf('<th scope="row" valign="top"><label for="%s">%s</label></th>',
			$conf['name'], $conf['label']
		);
		printf('<td><select name="%1$s" id="%1$s">', $conf['name']);

		printf('<option value=""%2$s>%1$s</option>',
			__('-- Select --', 'ptf'),
			selected($flag, '', false)
		);
		foreach ($conf['countries'] as $iso => $name) {
			printf('<option value="%1$s"%3$s>%2$s</option>',
				$iso, $name, selected($flag, $iso, false)
			);
		}

		print ('</select>');
		$flag_src = (!$flag ?: sprintf($conf['url'], $flag));
		printf('<img src="%2$s" alt="%1$s" id="flag_preview">',
			$conf['label'], $flag_src
		);
		print ('</td></tr>');
	}

	function save_term_fields($term_id) {
		$conf = $this->get_countries_conf();

		if (isset($_POST[ $conf['name'] ])) {
			update_term_meta($term_id, $conf['term'], sanitize_key( $_POST[ $conf['name'] ] ));
		}
	}

	function dashboard_glance($elements) {
		parent::dashboard_glance($elements);
	}
}

new Post_Type_Partner;
?>
