<?php
/*
Plugin Name: Post Type Framework Plugin
Description: Creates the required post types in a theme-independent way.
Version: 1.0.0
Author: Andrei Ioniță
Author URI: https://andrei.io/
Text Domain: ptf
*/

class Post_Type_Framework {
	protected $post_type_name;
	protected $post_type_args;
	private $activity_types = array('post', 'page', 'partner');

	public function __construct() {

		// No need to run this for child classes
		if (get_parent_class($this))
			return;

		load_muplugin_textdomain('ptf');

		foreach (glob(dirname(__FILE__) . '/post-type-framework/post-type-framework-*.php') as $filename) {
			require_once($filename);
		}

		add_action('load-edit.php'         , array($this, 'admin_force_excerpt'));
		add_action('wp_dashboard_setup'    , array($this, 'dashboard_activity_setup'));
		add_action('admin_enqueue_scripts' , array($this, 'load_assets'));
	}

	function register_post_type() {
		if (empty($this->post_type_name) || empty($this->post_type_args))
			return;

		register_post_type($this->post_type_name, $this->post_type_args);

		add_rewrite_tag('%ptf_filter%','([^&]+)');

		add_rewrite_rule(
			'^([a-z]{2}/)?json/([a-z0-9]+)',
			'index.php?ptf_filter=$matches[2]',
			'top'
		);

		if (empty($this->post_type_taxonomy_name) || empty($this->post_type_taxonomy_args))
			return;

		register_taxonomy($this->post_type_taxonomy_name, array($this->post_type_name), $this->post_type_taxonomy_args);
	}

	function get_meta_box_values($post_id) {
		return get_post_meta($post_id, "_ptf_{$this->post_type_name}_meta", true);
	}

	function save_meta_box_values($post_id, $values = array()) {
		if (empty($values))
			return;

		update_post_meta($post_id, "_ptf_{$this->post_type_name}_meta", $values);
	}

	function render_meta_box_field($name, $options = array(), $value = '') {
		switch ($options['type']) {
			case 'hidden':
				printf('<input type="hidden" name="ptf[%1$s][%2$s]" id="ptf_%2$s" value="%3$s">',
					$this->post_type_name, $name, $value
				);
				break;

			case 'textarea':
				printf('<tr class="ptf_meta"><th><label for="ptf_%2$s">%3$s</label></th><td><textarea  name="ptf[%1$s][%2$s]" id="ptf_%2$s" class="widefat%6$s" rows="%4$s">%5$s</textarea></td></tr>',
					$this->post_type_name, $name, $options['label'], $options['rows'], $value,
					(!!$options['i18n'] ? ' '.$options['i18n'] : '')
				);
				break;
			
			default:
				printf('<tr class="ptf_meta"><th><label for="ptf_%2$s">%4$s</label></th><td><input type="%3$s" name="ptf[%1$s][%2$s]" id="ptf_%2$s" class="widefat%6$s" value="%5$s"></td></tr>',
					$this->post_type_name, $name, $options['type'], $options['label'], esc_attr($value),
					(!!$options['i18n'] ? ' '.$options['i18n'] : '')
				);
				break;
		}
	}

	/**
	 * @TODO: sanitize_meta_box_value
	 * @param	mixed	$key	[description]
	 * @param	mixed	$value	[description]
	 * @return 	mixed			False on fail, sanitized value on success
	 */
	function sanitize_meta_box_value($key, $value) {
		$fields = $this->get_meta_box_fields();

		return $value;
	}

	function load_assets() {
		// wp_enqueue_style('ptf-backend', plugins_url('post-type-framework/assets/ptf-backend.css', __FILE__), false, null);
	}

	/**
	 * Persistent posts list mode
	 */
	function admin_force_excerpt() {
		$user_id = get_current_user_id();
		$meta_key = '_ptf_posts_list_mode';
		$save_mode = false;

		if (isset($_REQUEST['mode'])) {
			// save the list mode
			$save_mode = true;
		} elseif ($mode = get_user_meta($user_id, $meta_key, true)) {
			if (in_array($mode, array('list', 'excerpt'))) {
				// Valid mode, retrieve
				$_REQUEST['mode'] = $mode;
			} else {
				// Invalid mode, reset
				$_REQUEST['mode'] = 'excerpt';
				$save_mode = true;
			}
		}

		if ($save_mode) {
			update_user_meta($user_id, $meta_key, $_REQUEST['mode']);
		}
	}

	function dashboard_activity_setup() {
		global $wp_meta_boxes;

		unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);
		unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);

		wp_add_dashboard_widget('dashboard_activity', __('Activity'), array($this, 'dashboard_activity_widget'));
	}

	function dashboard_glance_items($elements) {
		$posts = wp_count_posts($this->post_type_name);

		if (!$posts || !$posts->publish)
			return;

		$singular = '%s '. $this->post_type_args['labels']['singular_name'];
		$plural   = '%s '. $this->post_type_args['labels']['name'];

		$elements[] = sprintf('<a class="count-%1$s" href="edit.php?post_type=%1$s">%2$s</a>',
			$this->post_type_name,
			sprintf(_n($singular, $plural, $posts->publish , 'ptf'),
				number_format_i18n($posts->publish)
			)
		);

		return $elements;
	}

	function dashboard_activity_widget() {
		echo '<div id="activity-widget">';

		$future_posts = $this->dashboard_activity_widget_items(array(
			'display' => 2,
			'max'     => 5,
			'status'  => 'future',
			'type'    => $this->activity_types,
			'order'   => 'ASC',
			'title'   => __('Publishing Soon'),
			'id'      => 'future-posts',
		));

		$recent_posts = $this->dashboard_activity_widget_items(array(
			'display' => 2,
			'max'     => 5,
			'status'  => 'publish',
			'type'    => $this->activity_types,
			'order'   => 'DESC',
			'title'   => __('Recently Published'),
			'id'      => 'published-posts',
		));

		$recent_comments = wp_dashboard_recent_comments();

		if (!$future_posts && !$recent_posts)
			printf('<div class="no-activity">%s%s</div>',
				'<p class="smiley"></p>',
				sprintf('<p>%s</p>',__('No activity yet!'))
			);

		echo '</div>';
	}

	function dashboard_activity_widget_items($args) {
		$query_args = array(
			'post_type'      => $args['type'],
			'post_status'    => $args['status'],
			'orderby'        => 'date',
			'order'          => $args['order'],
			'posts_per_page' => intval($args['max']),
			'no_found_rows'  => true,
			'cache_results'  => false
		);

		$posts = new WP_Query($query_args);

		if (!$posts->have_posts()) 
			return false;

		printf('<div id="%s" class="activity-block">', $args['id']);

		printf('<h4>%s</h4>', $args['title']);

		echo '<ul>';

		$i = 0;
		$today    = date('Y-m-d', current_time('timestamp'));
		$tomorrow = date('Y-m-d', strtotime('+1 day', current_time('timestamp')));

		while ($posts->have_posts()) {
			$posts->the_post();

			$time = get_the_time('U');
			if (date('Y-m-d', $time) == $today) {
				$relative = __('Today');
			} elseif (date('Y-m-d', $time) == $tomorrow) {
				$relative = __('Tomorrow');
			} else {
				/* translators: date and time format for recent posts on the dashboard, see http://php.net/date */
				$relative = date_i18n(__('M jS'), $time);
			}

			$post_type = ucfirst(get_post_type());
			if (current_user_can('edit_post', get_the_ID())) {
				/* translators: 1: relative date, 2: time, 3: post edit link, 4: post title */
				$format = sprintf(__('<span>%1$s, %2$s</span> <a href="%3$s">%4$s</a> (%5$s)'),
					$relative,
					get_the_time(),
					get_edit_post_link(),
					_draft_or_post_title(),
					__($post_type, 'ptf')
				);
			} else {
				/* translators: 1: relative date, 2: time, 3: post title */
				$format = sprintf(__('<span>%1$s, %2$s</span> %3$s'),
					$relative,
					get_the_time(),
					_draft_or_post_title(),
					__($post_type, 'ptf')
				);
			}

			printf('<li>%s</li>', $format);
		}

		echo '</ul>';
		echo '</div>';

		wp_reset_postdata();

		return true;
	}
}

new Post_Type_Framework;
?>
