<?php

namespace malamute;

defined('ABSPATH') or exit;

/**
 * Returns supported CodeMirror themes
 * @return {void}
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

function codemirror_get_theme_by_name($theme_name) {
	global $codemirror_themes;
	if ( array_key_exists($theme_name, $codemirror_themes) ) {
		return $codemirror_themes[$theme_name];
	}
	return false;
}