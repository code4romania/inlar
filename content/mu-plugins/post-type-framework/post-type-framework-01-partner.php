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
		add_action('init'									, array($this, 'register_post_type'));
		add_action('add_meta_boxes_'.$this->post_type_name	, array($this, 'add_meta_box'));
		add_action('save_post'								, array($this, 'save_meta_box_values'));

		add_filter('manage_'.$this->post_type_name.'_posts_columns'	, array($this, 'manage_posts_columns'));
		add_action('manage_'.$this->post_type_name.'_posts_custom_column'	, array($this, 'manage_posts_custom_column'));

		// Register custom post types into the dashboard glance widget
		add_filter('dashboard_glance_items'					, array($this, 'dashboard_glance_items'));
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
			'menu_icon'				=> 'dashicons-location-alt',
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
			'rewrite'				=> array('slug' => 'indicator', 'with_front' => false),
			'capability_type'		=> 'post'
		);

		$this->post_type_taxonomy_name = 'country';
		$this->post_type_taxonomy_args = array(
			'labels'			=> array(
				'name'				=> __('Countries', 'ptf'),
				'singular_name'		=> __('Country', 'ptf'),
				'search_items'		=> __('Search Countries', 'ptf'),
				'all_items'			=> __('All Countries', 'ptf'),
				'edit_item'			=> __('Edit Country', 'ptf'),
				'update_item'		=> __('Update Country', 'ptf'),
				'add_new_item'		=> __('Add new Country', 'ptf'),
				'new_item_name'		=> __('New Country', 'ptf'),
				'not_found'			=> __('No categories found.', 'ptf'),
				'menu_name'			=> __('Countries', 'ptf')
			),
			'hierarchical'		=> true,
			'show_ui'			=> true,
			'show_admin_column'	=> true,
			'query_var'			=> true,
			'rewrite'			=> array('slug' => 'country', 'with_front' => false)
		);

		$this->post_type_args['taxonomies'] = array($this->post_type_taxonomy_name);

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
			'chart_id'	=> __('ID Chart', 'ptf'),
		);
	}

	function get_default_values() {
		$defaults = array(
			'chart_id'	=> 0,
		);

		return $defaults;
	}

	function build_dropdown_options($type = '', $selected = 0) {
		$types = array(
			'chart_id'	=> 'chart',
		);
		$options = '';

		switch ($types[$type]) {
			case 'chart':
			case 'indicator':

				if (!post_type_exists($types[$type]))
					return $options;

				$options.= sprintf('<option value="0">%s</option>', __('Select&hellip;', 'ptf'));

				$posts = get_posts(array(
					'posts_per_page'	=> -1,
					'post_type'			=> $types[$type],
					'post_status'		=> 'publish',
				));

				foreach ($posts as $post) {
					$options.= sprintf('<option value="%1$s"%4$s>%2$s (%3$s)</option>',
						$post->ID, $post->post_title, get_the_date('', $post->ID),
						selected($selected, $post->ID, false)
					);
				}
				break;
		}

		return $options;
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
		foreach ($fields as $key => $label) {
			printf('<tr class="cmb-type-text ptf_meta"><th><label for="ptf_%1$s">%2$s</label></th><td><select name="ptf[%4$s][%1$s]" id="ptf_%1$s" class="widefat">%3$s</select></td></tr>',
				$key, $label, $this->build_dropdown_options($key, $values[$key]), $this->post_type_name
			);
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

	function manage_posts_columns($columns) {
		$columns = array(
			'cb'		=> '<input type="checkbox" />',
			'thumb'		=> __('Image', 'ptf'),
			'title'		=> __('Title'),
			'taxonomy-indicator_category'	=> $columns['taxonomy-indicator_category'],
			'author'	=> __('Author'),
			'date'		=> __('Date')
		);

		return $columns;
	}

	function manage_posts_custom_column($column) {
		global $post;

		switch ($column) {
			case 'thumb':
				the_post_thumbnail('thumbnail');
				break;
		}
	}

	function dashboard_glance($elements) {
		parent::dashboard_glance($elements);
	}
}

new Post_Type_Partner;
?>
