<?php
// Load database info and local development parameters
if (file_exists(dirname(__FILE__) . '/local-config.php')) {
	define('WP_LOCAL_DEV',   true);
	include(dirname(__FILE__) . '/local-config.php');
} else {
	define('WP_LOCAL_DEV',   false);
	define('DB_NAME',       '%%DB_NAME%%');
	define('DB_USER',       '%%DB_USER%%');
	define('DB_PASSWORD',   '%%DB_PASSWORD%%');
	define('DB_HOST',       '%%DB_HOST%%');
}

// You almost certainly do not want to change these
define('DB_CHARSET', 'utf8');
define('DB_COLLATE', '');

// Salts, for security
// Change these in config/secrets/salts.yaml
define('AUTH_KEY',         '%%AUTH_KEY%%');
define('SECURE_AUTH_KEY',  '%%SECURE_AUTH_KEY%%');
define('LOGGED_IN_KEY',    '%%LOGGED_IN_KEY%%');
define('NONCE_KEY',        '%%NONCE_KEY%%');
define('AUTH_SALT',        '%%AUTH_SALT%%');
define('SECURE_AUTH_SALT', '%%SECURE_AUTH_SALT%%');
define('LOGGED_IN_SALT',   '%%LOGGED_IN_SALT%%');
define('NONCE_SALT',       '%%NONCE_SALT%%');

// Table prefix
// Change this if you have multiple installs in the same database
$table_prefix  = 'inlar_';

// Language
// Leave blank for American English
// define('WPLANG', '');

// Custom Content Directory
define('WP_CONTENT_DIR', dirname(__FILE__) . '/content');
define('WP_CONTENT_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/content');

// Stage identifier
define('WP_STAGE', '%%WP_STAGE%%');

// Error handling
if (WP_LOCAL_DEV || WP_STAGE !== 'production') {
	// Always show errors in non-prod environment
	ini_set('display_errors', 1);
	define('WP_DEBUG', true);
	define('WP_DEBUG_LOG', true);
	define('WP_DEBUG_DISPLAY', true);
	define('SAVEQUERIES', true);
} else {
	// Hide errors in prod
	ini_set('display_errors', 0);
	define('WP_DEBUG', false);
	define('WP_DEBUG_LOG', false);
	define('WP_DEBUG_DISPLAY', false);
	define('SAVEQUERIES', false);
}

// Disable editing in dashboard
define('DISALLOW_FILE_EDIT', true);

// Bootstrap WordPress
if (!defined('ABSPATH'))
	define('ABSPATH', dirname(__FILE__) . '/wp/');

require_once(ABSPATH . 'wp-settings.php');
