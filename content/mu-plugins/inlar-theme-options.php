<?php

class INLAR_Options {
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

		add_action('admin_menu',	    array($this, 'add_menu_page'));
		add_filter('admin_init',	    array($this, 'register_settings'));
		add_filter('i18n_admin_config', array($this, 'i18n_support'));
	}

	function init_settings() {
		$this->config['settings'] = array(
			array(
				'id'       => 'inlar_partners',
				'title'    => __('Partners', 'ptf'),
			),
		);
	}

	function init_fields() {
		$this->config['fields']['inlar_partners'] = array(
			'section_title' => array(
				'title'   => __('Section title', 'inlar'),
				'type'    => 'text',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
			'section_text' => array(
				'title'   => __('Section text', 'inlar'),
				'type'    => 'textarea',
				'i18n'    => 'i18n-multilingual',
				'default' => '',
			),
		);
	}

	function register_settings() {
		foreach ($this->config['settings'] as $setting) {
			// Register db setting
			register_setting($this->config['slug'], $setting['id'], array(
				'sanitize_callback' => null
			));

			/**
			 * Doing manual sanitize_callback because
			 * of a {@link https://core.trac.wordpress.org/ticket/18914#comment:4 back compat issue from 2011!}
			 */
			add_filter("sanitize_option_{$setting['id']}", array($this, 'sanitize_option_data'), 10, 3);

			// Add a section for the new setting
			add_settings_section($setting['id'], $setting['title'], null, $this->config['slug']);

			// No fields, no dice
			if (!isset($this->config['fields'][ $setting['id'] ]))
				continue;

			foreach ($this->config['fields'][ $setting['id'] ] as $id => $field) {
				add_settings_field(
					$id,
					$field['title'],
					array($this, 'render_fields_html'),
					$this->config['slug'],
					$setting['id'],
					wp_parse_args($field, array(
						'section'   => $setting['id'],
						'label_for' => $id,
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

	function render_menu_page() {
		echo '<div class="wrap">';
		printf('<h1>%s</h1>', $this->config['name']);
		print ('<form method="post" action="options.php">');

		settings_fields($this->config['slug']);
		do_settings_sections($this->config['slug']);

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
			$defaults[ $id ] = $field['default'] ?: '';
		}

		return $defaults;
	}

	/**
	 * Adds qTranslate-X support for the custom options page
	 * 
	 * @param   array  $config
	 * @return  array
	 */
	function i18n_support($config) {
		$config['theme-options'] = array(
			'pages' => array(
				'themes.php' => '^page=theme-options.*$',
			),
		);

		return $config;
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
				printf('<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="regular-text %4$s">',
					$args['label_for'], $args['section'],
					$data[ $args['label_for'] ], $args['i18n'] ?: ''
				);
				break;

			case 'number':
				printf('<input type="number" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="">',
					$args['label_for'], $args['section'],
					$data[ $args['label_for'] ]
				);
				break;

			case 'date':
				printf('<input type="text" id="%1$s" name="%2$s[%1$s]" value="%3$s" class="datepicker">',
					$args['label_for'], $args['section'],
				 	strftime('%Y-%m-%d', $data[ $args['label_for'] ])
				);
				break;

			case 'textarea':
				printf('<textarea id="%1$s" name="%2$s[%1$s]" rows="10" class="large-text %4$s">%3$s</textarea>',
					$args['label_for'], $args['section'],
					$data[ $args['label_for'] ], $args['i18n'] ?: ''
				);
				break;

			case 'checkbox':
				printf('<label for="%1$s"><input type="checkbox" id="%1$s" name="%2$s[%1$s]" value="1"%3$s> %4$s</label>',
					$args['label_for'], $args['section'],
					checked($data[$args['label_for']], '1', false),
					isset($args['label_text']) ? $args['label_text'] : ''
				);
				break;

			case 'pages':
				wp_dropdown_pages(array(
					'show_option_none'	=> sprintf('- %s -', __('None')),
					'name'				=> sprintf('%s[%s]', $args['section'], $args['label_for']),
					'id'				=> $args['label_for'],
					'selected'			=> $data[ $args['label_for'] ],
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
		if (!isset($this->config['fields'][ $option ]))
			wp_die(__('Are you sure you want to do this?'));

		foreach ($data as $key => $value) {
			switch ($this->config['fields'][ $option ][ $key ]['type']) {
				case 'text':
					$data[$key] = sanitize_text_field($value);
					break;
				
				case 'textarea':
					$data[$key] = sanitize_textarea_field($value);
			}
		}

		return $data;
	}
}

if (is_admin()) {
	$inlar_options = new INLAR_Options();
}
?>
