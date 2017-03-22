<?php
/*
Plugin Name: Multilingual trasient support plugin
Description: 
Version: 1.0.0
Author: Andrei Ioniță
Author URI: https://andrei.io/
*/

class i18n_utils {
	// Quick and dirty way to check if qTranslate-X is installed and running
	public static function is_i18n_plugin_installed() {
		$is_installed = true;

		$checks = array('qtranxf_getLanguage', 'qtranxf_getSortedLanguages');

		foreach ($checks as $fn) {
			if (!function_exists($fn))
				$is_installed = false;
		}

		return $is_installed;
	}

	/**
	 * Set transient for current language.
	 * @param   string   $transient  Transient name, not SQL-escaped. Should be less than 45 chars.
	 * @param   mixed    $value      Transient value, not SQL-escaped.
	 * @param   integer  $expiration Time until expiration in seconds from now, or 0 for never expires.
	 * @param   boolean  $nolang     Flag for language-independent content
	 * @return  boolean              False if value was not set and true if value was set.
	 */
	public static function set_transient($transient, $value, $expiration, $nolang = false) {
		if (!self::is_i18n_plugin_installed())
			return false;

		if (empty($transient))
			return false;

		$lang = ($nolang ? 'nolang' : qtranxf_getLanguage());

		return set_transient("{$transient}_{$lang}", $value, $expiration);
	}

	/**
	 * Get transient value for current language.
	 * @param   string   $transient	 Transient name, not SQL-escaped.
	 * @param   string   $nolang     Flag for language-independent content
	 * @return  mixed                Value of transient or false.
	 */
	public static function get_transient($transient,  $nolang = false) {
		if (!self::is_i18n_plugin_installed())
			return false;


		if (empty($transient))
			return false;

		$lang = ($nolang ? 'nolang' : qtranxf_getLanguage());

		return get_transient("{$transient}_{$lang}");
	}

	/**
	 * Delete transient for all languages.
	 * @param   string  $transient   Transient name, not SQL-escaped.
	 * @param   string   $nolang     Flag for language-independent content
	 * @return	boolean				 False if any unsuccessful, true otherwise.
	 */
	public static function delete_transient($transient, $nolang = false) {
		if (!self::is_i18n_plugin_installed())
			return false;

		if (empty($transient))
			return false;

		if ($nolang) {
			return delete_transient("{$transient}_nolang");
		}

		$result = true;
		$languages = qtranxf_getSortedLanguages();

		foreach ($languages as $lang) {
			if (!delete_transient("{$transient}_{$lang}"))
				$result = false;
		}

		return $result;
	}
}
