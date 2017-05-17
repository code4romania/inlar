<?php

class INLAR_Contact_Form {
	private $fields;

	function __construct() {
		$this->fields = array(
			array(
				'label'       => __('Your name', 'inlar'),
				'name'        => 'name',
				'type'        => 'text',
				'req'         => true,
				'minlength'   => 5,
			),
			array(
				'label'       => __('Your email', 'inlar'),
				'name'        => 'email',
				'type'        => 'email',
				'req'         => true,
			),
			array(
				'label'       => __('Subject', 'inlar'),
				'placeholder' => __('How can we help you?', 'inlar'),
				'name'        => 'subject',
				'type'        => 'text',
				'req'         => true,
				'minlength'   => 10,
			),
			array(
				'label'       => __('Message', 'inlar'),
				'placeholder' => __('Detail your story here', 'inlar'),
				'name'        => 'message',
				'type'        => 'textarea',
				'req'         => true,
				'minlength'   => 10,
			),
			array(
				'label'       => __('Send now', 'inlar'),
				'name'        => 'submit',
				'type'        => 'submit',
			),
		);

		add_action('inlar_contact_form_display', array($this, 'form_action'));

		add_action('wp_ajax_nopriv_inlar_contact_form', array($this, 'form_process'));
		add_action('wp_ajax_inlar_contact_form', array($this, 'form_process'));

		add_action('wp_enqueue_scripts', array($this, 'validation_messages'), 20);		
	}

	function render_field($field) {
		printf('<label for="%1$s" class="screen-reader-text focusable">%2$s</label>',
			$field['name'], $field['label']
		);

		switch ($field['type']) {
			case 'email':
			case 'text':
				printf('<input type="%1$s" name="%2$s" id="%2$s" placeholder="%3$s" class="text" value="" %4$s%5$s>',
					$field['type'], $field['name'], isset($field['placeholder']) ? $field['placeholder'] : $field['label'],
					($field['req'] ? ' aria-required="true" aria-invalid="false" required' : ''),
					(isset($field['minlength']) ? sprintf(' minlength="%d"', $field['minlength']) : '')
				);
				break;

			case 'textarea':
				printf('<textarea name="%1$s" id="%1$s" rows="10" placeholder="%2$s" class="text" %3$s%4$s></textarea>',
					$field['name'], isset($field['placeholder']) ? $field['placeholder'] : $field['label'],
					($field['req'] ? ' aria-required="true" aria-invalid="false" required' : ''),
					(isset($field['minlength']) ? sprintf(' minlength="%d"', $field['minlength']) : '')
				);
				break;

			case 'submit':
				wp_nonce_field('contact_form', '_wpnonce', false);
				printf('<input type="submit" class="button button-block" value="%s">',
					__('Send now', 'inlar')
				);
				break;
		}
	}

	function render_form($fields = array()) {
		print ('<form method="post" class="form">');

		foreach ($fields as $field) {
			print ('<div class="field">');
			$this->render_field($field);
			print ('</div>');
		}

		print ('<div class="response-container"></div>');
		print ('</form>');
	}

	function form_action() {
		$this->render_form($this->fields);
	}

	function form_process() {
		parse_str($_POST['data'], $data);

		if (empty($data) || empty($data['_wpnonce']) || !wp_verify_nonce($data['_wpnonce'], 'contact_form')) {
			wp_send_json_error(array(
				'title'  => __('Error', 'inlar'),
				'text'   => __('An unexpected error has occured. Please try again later.', 'inlar'),
				'button' => __('Back', 'inlar'),
			));
		} else if (!is_email($data['email'])) {
			wp_send_json_error(array(
				'title'  => __('Error', 'inlar'),
				'text'   => __('Your email doesn\'t seem to be valid. Please check it and try again.', 'inlar'),
				'button' => __('Back', 'inlar'),
			));
		} else {
			$opt = get_option('inlar_contact');

			$mail_sent = wp_mail(
				// To
				$opt['email'],
				// Subject
				sprintf(_x('[INLAR] %s', 'contact mail subject prefix', 'inlar'),
					sanitize_text_field($data['subject'])
				),
				// Message
				sanitize_textarea_field($data['message']),
				// Reply-To header
				sprintf('Reply-To: %s <%s>',
					sanitize_text_field($data['name']),
					sanitize_text_field($data['email'])
				)
			);

			if ($mail_sent) {
				wp_send_json_success(array(
					'title'  => __('Message Sent', 'inlar'),
					'text'   => sprintf(
						__('Thank you, %s! Your message was sent and we will try to reply in short time.', 'inlar'),
						$data['name']
					),
					'button' => __('Done', 'inlar'),
				));
			} else {
				wp_send_json_error(array(
					'title'  => __('Error', 'inlar'),
					'text'   => __('An unexpected error has occured. Please try again later.', 'inlar'),
					'button' => __('Back', 'inlar'),
				));
			}
		}
	}

	function validation_messages() {
		$messages = array(
			'required'  => _x('This field is required.', 'form validation message', 'inlar'),
			'email'     => _x('Please enter a valid email address.', 'form validation message', 'inlar'),
			'minlength' => _x('Please enter at least {0} characters.', 'form validation message', 'inlar'),
			'maxlength' => _x('Please enter no more than {0} characters.', 'form validation message', 'inlar'),
		);

		wp_localize_script('inlar-app', 'validation_messages', $messages);
	}
}

$inlar_contact = new INLAR_Contact_Form();

?>
