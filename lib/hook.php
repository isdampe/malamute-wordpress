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
	wp_enqueue_style('malamute-css-codemirror', plugins_url('malamute/assets/vendor/cm/lib/codemirror.css'));

	$user_theme = get_setting('malamute-editor-theme');
	$codemirror_theme = codemirror_get_theme_by_name($user_theme);
	if ( $codemirror_theme ) {
		wp_enqueue_style(sprintf('malamute-css-codemirror-theme-%s', $codemirror_theme['name']), plugins_url(sprintf('malamute/assets/vendor/cm/theme/%s', $codemirror_theme['file'])));
	}

	wp_enqueue_script('malamute-js-codemirror', plugins_url('malamute/assets/vendor/cm/lib/codemirror.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-overlay', plugins_url('malamute/assets/vendor/cm/addon/mode/overlay.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-xml', plugins_url('malamute/assets/vendor/cm/mode/xml/xml.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-markdown', plugins_url('malamute/assets/vendor/cm/mode/markdown/markdown.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-gfm', plugins_url('malamute/assets/vendor/cm/mode/gfm/gfm.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-javascript', plugins_url('malamute/assets/vendor/cm/mode/javascript/javascript.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-css', plugins_url('malamute/assets/vendor/cm/mode/css/css.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-htmlmixed', plugins_url('malamute/assets/vendor/cm/mode/htmlmixed/htmlmixed.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-clike', plugins_url('malamute/assets/vendor/cm/mode/xml/xml.js'));
	wp_enqueue_script('malamute-js-codemirror-mode-meta', plugins_url('malamute/assets/vendor/cm/mode/meta.js'));

	//Markdown
	wp_enqueue_style('malamute-css-editor', plugins_url('malamute/assets/css/malamute-editor.css'));
	wp_enqueue_script('malamute-js-editor', plugins_url('malamute/assets/js/malamute-editor.js'));

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