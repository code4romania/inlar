<?php
/**
 * Post Type Framework: NGO
 */

class Post_Type_NGO extends Post_Type_Framework {
	protected $post_type_name;
	protected $post_type_args;

	function __construct() {
		parent::__construct();

		$this->setup();

		// Hook into the actual registration process
		add_action('init', array($this, 'register_post_type'));
		add_action('add_meta_boxes_'.$this->post_type_name, array($this, 'add_meta_box'));
		add_action('save_post', array($this, 'save_meta_box_values'));
		add_action('after_delete_post', array($this, 'clear_transients'));

		// Taxonomy hooks
		add_action($this->post_type_taxonomy_name.'_add_form_fields', array($this, 'add_term_fields'));
		add_action($this->post_type_taxonomy_name.'_edit_form_fields', array($this, 'edit_term_fields'));

		add_action('edited_'.$this->post_type_taxonomy_name, array($this, 'save_term_fields'), 10, 2);
		add_action('create_'.$this->post_type_taxonomy_name, array($this, 'save_term_fields'), 10, 2);

		// 
		add_filter('ptf_get_countries', array($this, 'get_countries'));

		// Endpoint
		add_action('template_include', array($this, 'template_json'));

		// Register custom post types into the dashboard glance widget
		add_filter('dashboard_glance_items', array($this, 'dashboard_glance_items'));
	}

	/**
	 * Load config values into class object
	 */
	function setup() {
		// Setup custom post types
		$this->post_type_name	= 'ngo';
		$this->post_type_args	= array(
			'labels'				=> array(
				'name'					=> __('NGOs', 'ptf'),
				'singular_name'			=> __('NGO', 'ptf'),
				'add_new'				=> __('Add New', 'ptf'),
				'add_new_item'			=> __('Add NGO', 'ptf'),
				'edit_item'				=> __('Edit NGO', 'ptf'),
				'all_items'				=> __('All NGOs', 'ptf'),
				'view_item'				=> __('View NGO', 'ptf'),
				'search_items'			=> __('Search NGOs', 'ptf'),
				'not_found'				=> __('No NGOs found', 'ptf'),
				'not_found_in_trash'	=> __('No NGOs found in Trash', 'ptf'),
				'menu_name'				=> __('NGOs', 'ptf')
			),
			'menu_icon'				=> 'dashicons-location-alt',
			'hierarchical'			=> false,
			'supports'				=> array('title', 'editor', 'thumbnail'),
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


	function template_json($template) {
		global $wp_query;

		if (!isset($wp_query->query['ptf_filter']) || empty($wp_query->query['ptf_filter']))
			return $template;

		$filter_key   = $wp_query->query['ptf_filter'];

		if (!in_array($filter_key, array('ngos'))) {
			$this->send_json_response('error', array(
				'message' => __('Invalid request key', 'ptf')
			));
		} else {
			$this->send_json_response('success', $this->get_ngos_by_country());			
		}

		return $template;
	}

	function send_json_response($type = '', $data = array()) {
		switch ($type) {
			default:
			case 'error':
				$response = array(
					'error' => $data,
				);
				$code = 400;
				break;
			
			case 'success':
				$response = array(
					'data' => $data,
				);
				$code = 200;
				break;
		}

		header('Content-type: application/json');
		header('Pragma: no-cache');
		header('Expires: 0');
		status_header($code);

		echo json_encode($response);

		exit;
	}

	function get_countries() {
		$conf = $this->get_countries_conf();

		$countries = array();
		
		$terms = get_terms(array(
			'taxonomy'   => $this->post_type_taxonomy_name,
			'hide_empty' => true,
		));

		foreach ($terms as $term) {
			$countries[$term->slug] = array(
				'id'      => $term->term_id,
				'name'    => $term->name,
				'flag'    => get_term_meta($term->term_id, $conf['term'], true),
			);
		}

		return $countries;
	}

	function get_ngo_data($post) {
		$meta = $this->get_meta_box_values($post->ID);
		$main = array(
			'name' => get_the_title($post),
			'desc' => sanitize_post_field('post_content', $post->post_content, $post->ID, 'display'), 
		);

		return wp_parse_args($meta, $main);
	}

	function get_ngos_by_country() {
		$transient = 'ptf_ngos_by_country';

		if (false === ($countries = i18n_utils::get_transient($transient))) {
			$countries = $this->get_countries();

			foreach ($countries as $slug => $values) {
				$ngos = new WP_Query(array(
					'posts_per_page' => -1,
					'tax_query'      => array(
						array(
							'taxonomy' => $this->post_type_taxonomy_name,
							'field'    => 'slug',
							'terms'    => $slug,
						),
					),
				));

				$countries[$slug]['ngos'] = array();

				foreach ($ngos->posts as $ngo) {
					$countries[$slug]['ngos'][] = $this->get_ngo_data($ngo);
				}
			}

			i18n_utils::set_transient($transient, $countries, MINUTE_IN_SECONDS); // @TODO: use proper expiration value
		}

		return $countries;
	}

	/**
	 * Add a custom meta box
	 */
	function add_meta_box() {
		$fields = $this->get_meta_box_fields();
		if (empty($fields))
			return;

		add_meta_box(
			'ptf_info',
			__('Additional Information', 'ptf'),
			array($this, 'render_meta_box'),
			$this->post_type_name,
			'advanced',
			'high'
		);
	}

	function get_meta_box_fields() {
		return array(
			'coords'   => array(
				'label'    => __('Coordinates', 'ptf'),
				'type'     => 'text',
				'i18n'     => false,
			),
			'address'  => array(
				'label'    => __('Address', 'ptf'),
				'type'     => 'textarea',
				'i18n'     => 'i18n-multilingual',
				'rows'     => 5,
			),
			'url'      => array(
				'label'    => __('URL', 'ptf'),
				'type'     => 'url',
				'i18n'     => false,
			),
			'keywords' => array(
				'label'    => __('Keywords', 'ptf'),
				'type'     => 'text',
				'i18n'     => 'i18n-multilingual',
			),
			'phone'    => array(
				'label'    => __('Phone', 'ptf'),
				'type'     => 'text',
				'i18n'     => false,
			),
		);
	}

	function get_default_values() {
		return array(
			'coords'   => '',
			'address'  => '',
			'url'      => '',
			'keywords' => '',
			'phone'    => '',
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

		// Add map container for leaflet
		print ('<div id="map"></div>');

		print('<table class="form-table cmb_metabox">');
		foreach ($fields as $name => $options) {
			parent::render_meta_box_field($name, $options, $values[$name]);
		}
		print('</table>');
	}

	/**
	 * [sanitize_meta_box_value description]
	 * @param	mixed	$value	[description]
	 * @return 	mixed			False on fail, sanitized value on success
	 */
	function sanitize_meta_box_value($value) {
		$fields = $this->get_meta_box_fields();

		return $value;
	}

	/**
	 * Save custom data on post save.
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

		// Sanitize and save
		foreach ($values as $k => $v) {
			$sanitized = $this->sanitize_meta_box_value($v);

			if ($sanitized === false)
				continue;

			update_post_meta($post_id, "_ptf_{$this->post_type_name}_{$k}_meta", $sanitized);
		}


		i18n_utils::delete_transient('ptf_ngos_by_country');

		// parent::save_meta_box_values($post_id, $values);
	}

	function clear_transients($post_id) {
		if (get_post_type($post->ID) !== $this->post_type_name)
			return false;

		i18n_utils::delete_transient('ptf_ngos_by_country');		
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

		i18n_utils::delete_transient('ptf_ngos_by_country');
	}

	function dashboard_glance($elements) {
		parent::dashboard_glance($elements);
	}
}

new Post_Type_NGO;
?>
