<?php

class INLAR_Theme_Options {
	private $config;
	
	function __construct() {
		$this->config = array(
			'name'     => __('Theme Options'),
			'cap'      => 'edit_theme_options',
			'slug'     => 'theme-options',
			'settings' => array(),
			'fields'   => array(),
		);

		$this->init_settings();
		$this->init_fields();

		add_action('admin_menu',        array($this, 'add_menu_page'));
		add_action('admin_init',        array($this, 'register_settings'));
	}

	function init_settings() {
		$this->config['settings'] = array(
			array(
				'id'       => 'inlar_intro',
				'title'    => __('Intro', 'inlar'),
			),
			array(
				'id'       => 'inlar_partners',
				'title'    => __('Partners', 'inlar'),
			),
			array(
				'id'       => 'inlar_news',
				'title'    => __('News', 'inlar'),
			),
			array(
				'id'       => 'inlar_team',
				'title'    => __('Team', 'inlar'),
			),
			array(
				'id'       => 'inlar_contact',
				'title'    => __('Contact', 'inlar'),
			),
		);
	}

	function init_list($post_type) {
		$items = get_posts(array(
			'post_type'      => $post_type,
			'posts_per_page' => -1,
		));

		$list = array();

		foreach ($items as $item) {
			$list[] = array(
				'value' => $item->ID,
				'name'  => get_the_title($item),
			);
		}

		return $list;
	}

	function init_fields() {
		$this->config['fields']['inlar_intro'] = array(
			'title' => array(
				'title'   => __('Section title', 'inlar'),
				'type'    => 'text',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'text' => array(
				'title'   => __('Section text', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
		);

		$this->config['fields']['inlar_partners'] = array(
			'title' => array(
				'title'   => __('Section title', 'inlar'),
				'type'    => 'text',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'text' => array(
				'title'   => __('Section text', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'featured'     => array(
				'title'   => __('Featured on the front-page', 'inlar'),
				'type'    => 'select-items',
				'slots'   => 4,
				'default' => array(0, 0, 0, 0),
				'options' => $this->init_list('partner'),
			),
		);

		$this->config['fields']['inlar_news'] = array(
			'title' => array(
				'title'   => __('Section title', 'inlar'),
				'type'    => 'text',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'text' => array(
				'title'   => __('Section text', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
		);

		$this->config['fields']['inlar_team'] = array(
			'title' => array(
				'title'   => __('Section title', 'inlar'),
				'type'    => 'text',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'text' => array(
				'title'   => __('Section text', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
		);

		$this->config['fields']['inlar_contact'] = array(
			'title' => array(
				'title'   => __('Section title', 'inlar'),
				'type'    => 'text',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'text' => array(
				'title'   => __('Section text', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'email'    => array(
				'title'   => __('Email address', 'inlar'),
				'type'    => 'email',
				'i18n'    => false,
				'default' => '',
			),
			'phone'    => array(
				'title'   => __('Phone', 'inlar'),
				'type'    => 'text',
				'i18n'    => false,
				'default' => '',
			),
			'address'    => array(
				'title'   => __('Address', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => false,
				'default' => '',
			),
			'facebook'    => array(
				'title'   => __('Facebook profile', 'inlar'),
				'type'    => 'url',
				'i18n'    => false,
				'default' => '',
			),
			'twitter'    => array(
				'title'   => __('Twitter profile', 'inlar'),
				'type'    => 'url',
				'i18n'    => false,
				'default' => '',
			),
			'instagram'    => array(
				'title'   => __('Instagram profile', 'inlar'),
				'type'    => 'url',
				'i18n'    => false,
				'default' => '',
			),
			'medium'    => array(
				'title'   => __('Medium profile', 'inlar'),
				'type'    => 'url',
				'i18n'    => false,
				'default' => '',
			),
		);
	}

	function register_settings() {
		do_action('inlar_before_options');
		foreach ($this->config['settings'] as $setting) {
			$settings_page = sprintf('%s-%s',
				$this->config['slug'],
				$setting['id']
			);
			// Register db setting
			register_setting($settings_page, $setting['id'], array(
				'sanitize_callback' => null
			));

			/**
			 * Doing manual sanitize_callback because
			 * of a {@link https://core.trac.wordpress.org/ticket/18914#comment:4 back compat issue from 2011!}
			 */
			add_filter("sanitize_option_{$setting['id']}", array($this, 'sanitize_option_data'), 10, 3);

			// Add a section for the new setting
			add_settings_section($setting['id'], $setting['title'], null, $settings_page);

			// No fields, no dice
			if (!isset($this->config['fields'][ $setting['id'] ]))
				continue;

			foreach ($this->config['fields'][ $setting['id'] ] as $id => $field) {
				add_settings_field(
					$id,
					$field['title'],
					array($this, 'render_fields_html'),
					$settings_page,
					$setting['id'],
					wp_parse_args($field, array(
						'section'   => $setting['id'],
						'label_for' => $setting['id'] .'_'. $id,
						'id'        => $id,
					))
				);
			}
		}
	}

	function add_menu_page() {
		add_theme_page(
			$this->config['name'],
			$this->config['name'],
			$this->config['cap'],
			$this->config['slug'],
			array($this, 'render_menu_page')
		);
	}

	function render_tabs($active_tab) {
		print ('<h2 class="nav-tab-wrapper">');
		foreach ($this->config['settings'] as $setting) {
			printf('<a href="%s" class="nav-tab%s">%s</a>',
				add_query_arg(array(
					'page' => $this->config['slug'],
					'tab' => $setting['id'],
				), admin_url('themes.php', 'admin')),
				($active_tab == $setting['id'] ? ' nav-tab-active' : ''),
				$setting['title']
			);
		}
		print ('</h2>');
	}

	function render_menu_page() {
		if (isset($_GET['tab']) && in_array($_GET['tab'], array_column($this->config['settings'], 'id'))) {
			$active_tab = $_GET['tab'];
		} else {
			$active_tab = $this->config['settings'][0]['id'];
		}

		echo '<div class="wrap">';
		printf('<h1>%s</h1>', $this->config['name']);
		settings_errors($this->config['slug'], false, false);

		$this->render_tabs($active_tab);

		print ('<form method="post" action="options.php">');

		$settings_page = sprintf('%s-%s',
			$this->config['slug'],
			$active_tab
		);

		settings_fields($settings_page);
		do_settings_sections($settings_page);

		submit_button();
		print ('</form>');
		echo '</div>';
	}

	function defaults($section = '') {
		$defaults = array();

		if (!isset($this->config['fields'][ $section ]))
			return $defaults;

		if (empty($this->config['fields'][ $section ]))
			return defaults;

		foreach ($this->config['fields'][ $section ] as $id => $field) {
			$defaults[ $id ] = isset($field['default']) ? $field['default'] : '';
		}

		return $defaults;
	}

	/**
	 * Builds the options form
	 * 
	 * @param   array   $args   Arguments passed to add_settings_field()
	 * @return                  Prints out the fields html
	 */
	function render_fields_html($args) {
		$type = isset($args['type']) ? $args['type'] : '';
		$data = wp_parse_args(get_option($args['section'], array()), $this->defaults($args['section']));

		switch ($type) {
			case 'text':
			case 'email':
			case 'url':
				printf('<input type="%5$s" id="%2$s_%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text %4$s">',
					$args['id'], $args['section'],
					$data[ $args['id'] ], $args['i18n'] ?: '',
					$type
				);
				break;

			case 'number':
				printf('<input type="number" id="%2$s_%1$s" name="%2$s[%1$s]" value="%3$s" class="">',
					$args['id'], $args['section'],
					$data[ $args['id'] ]
				);
				break;

			case 'date':
				printf('<input type="text" id="%2$s_%1$s" name="%2$s[%1$s]" value="%3$s" class="datepicker">',
					$args['id'], $args['section'],
				 	strftime('%Y-%m-%d', $data[ $args['id'] ])
				);
				break;

			case 'textarea':
				printf('<textarea id="%2$s_%1$s" name="%2$s[%1$s]" rows="5" class="large-text %4$s">%3$s</textarea>',
					$args['id'], $args['section'],
					$data[ $args['id'] ], $args['i18n'] ?: ''
				);
				break;

			case 'checkbox':
				printf('<label for="%2$s_%1$s"><input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s> %4$s</label>',
					$args['id'], $args['section'],
					checked($data[$args['id']], '1', false),
					isset($args['label_text']) ? $args['label_text'] : ''
				);
				break;

			case 'select-items':
				print ('<table>');
				for ($i=0; $i < $args['slots']; $i++) {
					print ('<tr>');
					printf('<td><label for="%1$s_%3$d">#%4$d</label></td><td><select id="%1$s_%3$d" name="%2$s[%1$s][]">',
						$args['id'], $args['section'],
						$i, $i + 1
					);
					printf('<option value="0">-- %s --</option>',
						__('None')
					);

					foreach ($args['options'] as $option) {
						$compare = isset($data[ $args['id'] ][$i]) ? $data[ $args['id'] ][$i] : false;

						printf('<option value="%1$s" %3$s>%2$s</option>',
							$option['value'], __($option['name']),

							selected($option['value'], $compare, false)
						);
					}
					print ('</select></td>');
					print ('</tr>');
				}

				print ('</table>');
				break;

			case 'pages':
				wp_dropdown_pages(array(
					'show_option_none'	=> sprintf('- %s -', __('None')),
					'name'				=> sprintf('%s[%s]', $args['section'], $args['id']),
					'id'				=> $args['id'],
					'selected'			=> $data[ $args['id'] ],
				));
				break;
		}


		if (isset($args['description'])) {
			printf('<p class="description">%s</p>',
				$args['description']
			);
		}
	}

	/**
	 * [sanitize_option_data description]
	 *
	 * @todo sanitize other data types, as needed
	 * 
	 * @param   array   $data       User input
	 * @param   string  $option     Option name
	 * @return  array               Sanitized values
	 */
	function sanitize_option_data($data, $option) {
		$current   = get_settings_errors($this->config['slug']);
		$has_error = false;
		$errors    = array();

		if (!isset($this->config['fields'][ $option ]))
			wp_die(__('Are you sure you want to do this?'));

		foreach ($data as $key => $value) {
			switch ($this->config['fields'][ $option ][ $key ]['type']) {
				case 'text':
					$data[$key] = sanitize_text_field($value);
					break;
				
				case 'textarea':
					$data[$key] = sanitize_textarea_field($value);
					break;

				case 'select-items':
					$value = array_map('intval', $value);
					$validated = array();

					foreach ($value as $partner_id) {
						if (!$partner_id)
							continue;

						if (in_array($partner_id, $validated)) {
							$has_error = true;
							$errors[] = __('You cannot feature the same partner twice!', 'inlar');
							continue;
						}

						$validated[] = $partner_id;
					}
					$data[$key] = $validated;
					break;
			}
		}

		if ($has_error) {
			foreach ($errors as $message) {
				add_settings_error(
					$this->config['slug'],
					esc_attr('settings_updated'),
					$message,
					'error'
				);
			}
		} elseif (!in_array('updated', array_column($current, 'type'))) {
			add_settings_error(
				$this->config['slug'],
				esc_attr('settings_updated'),
				__('Settings saved.', 'inlar'),
				'updated'
			);
		}

		return $data;
	}
}

if (is_admin()) {
	$inlar_options = new INLAR_Theme_Options();
}
?>
