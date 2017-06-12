<?php

namespace malamute;

defined('ABSPATH') or exit;

/**
 * Removes the Wordpress default visual, and text editor.
 * $settings reference - https://codex.wordpress.org/Function_Reference/wp_editor
 * @param {array} $settings - The wordpress_editor settings array
 * @param {string} $editor_id - The editor ID
 * @return {void}
 */
function remove_wordpress_editor($settings, $editor_id) {
	if ( $editor_id == 'content' ) {
		$settings['tinymce']   = false;
		$settings['quicktags'] = false;
		$settings['media_buttons'] = false;
	}

	return $settings;
}
add_filter('wp_editor_settings', '\malamute\remove_wordpress_editor', 10, 2);


/**
 * Adds all required scripts to the WP script queue for admin users
 * @return {void}
 */
function enqueue_admin_scripts($hook) {

	//CodeMirror
	wp_enqueue_style('malamute-css-codemirror', sprintf("%sassets/vendor/cm/lib/codemirror.css", MALAMUTE_PLUGIN_BASEURL));

	$user_theme = get_setting('malamute-editor-theme');
	$codemirror_theme = codemirror_get_theme_by_name($user_theme);
	if ( $codemirror_theme ) {
		wp_enqueue_style(sprintf('malamute-css-codemirror-theme-%s', $codemirror_theme['name']), sprintf('%sassets/vendor/cm/theme/%s', MALAMUTE_PLUGIN_BASEURL, $codemirror_theme['file']));
	}

	wp_enqueue_script('malamute-js-codemirror', sprintf('%sassets/vendor/cm/lib/codemirror.js', MALAMUTE_PLUGIN_BASEURL));

	$modes = settings_get_active_codemirror_modes();
	foreach ( $modes as $mode_name ) {
		$the_mode = codemirror_get_mode_by_name($mode_name);
		if (! $the_mode ) continue;
		wp_enqueue_script(sprintf('malamute-js-codemirror-mode-%s', $the_mode['name']), sprintf('%sassets/vendor/cm/mode/%s/%s', MALAMUTE_PLUGIN_BASEURL, $the_mode['name'], $the_mode['file']));
	}

	
	wp_enqueue_script('malamute-js-codemirror-mode-overlay', sprintf('%sassets/vendor/cm/addon/mode/overlay.js', MALAMUTE_PLUGIN_BASEURL));

	//Markdown
	wp_enqueue_style('malamute-css-editor', sprintf('%sassets/css/malamute-editor.css', MALAMUTE_PLUGIN_BASEURL));
	wp_enqueue_script('malamute-js-editor', sprintf('%sassets/js/malamute-editor.js', MALAMUTE_PLUGIN_BASEURL));

}
add_action('admin_enqueue_scripts', '\malamute\enqueue_admin_scripts');

/**
 * Writes user preferences in the document so they are accessible via javascript
 * @return {void}
 */
function inject_user_js_preferences() {
	$user_theme = get_setting('malamute-editor-theme');
	
	//TODO - Escape this properly.
	echo '<script>window.malamuteTheme = "' . htmlspecialchars($user_theme) . '";</script>';
}
add_action('admin_footer', '\malamute\inject_user_js_preferences');