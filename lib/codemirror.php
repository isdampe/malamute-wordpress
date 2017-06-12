<?php

namespace malamute;

defined('ABSPATH') or exit;

/**
 * Returns supported CodeMirror themes
 * @return {array} - The array of supported CodeMirror themes
 */
function codemirror_get_themes() {
	global $codemirror_themes;
	return $codemirror_themes;
}

/**
 * Checks to confirm if a theme name exists for CodeMirror
 * @param {string} $theme_name - The name of the theme
 * @return {bool} - True if the theme exists, otherwise false
 */
function codemirror_theme_exists($theme_name) {
	global $codemirror_themes;
	if ( array_key_exists($theme_name, $codemirror_themes) ) {
		return true;
	}
	return false;
}

/**
 * Gets a codemirror theme by name
 * @param {string} $theme_name - The name of the theme to fetch
 * @return {array} - The array containing the codemirror theme information
 */
function codemirror_get_theme_by_name($theme_name) {
	global $codemirror_themes;
	if ( array_key_exists($theme_name, $codemirror_themes) ) {
		return $codemirror_themes[$theme_name];
	}
	return false;
}

/**
 * Returns supported CodeMirror modes
 * @return {array} - The array of supported CodeMirror modes
 */
function codemirror_get_modes() {
	global $codemirror_modes;
	return $codemirror_modes;
}

/**
 * Checks to see if a mode exists
 * @param {string} $mode_name - The name of the mode
 * @return {bool} - True if the mode exits, otherwise false
 */
function codemirror_mode_exists($mode_name) {
	global $codemirror_modes;

	if ( array_key_exists($mode_name, $codemirror_modes) ) {
		return true;
	}
	return false;
}

/**
 * Gets a codemirror mode by name
 * @param {string} $theme_name - The name of the mode to fetch
 * @return {array} - The array containing the codemirror mode information
 */
function codemirror_get_mode_by_name($mode_name) {
	global $codemirror_modes;
	if ( array_key_exists($mode_name, $codemirror_modes) ) {
		return $codemirror_modes[$mode_name];
	}
	return false;
}