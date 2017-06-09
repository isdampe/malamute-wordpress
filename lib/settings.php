<?php

namespace malamute;

defined('ABSPATH') or exit;


/**
 * Fetches and renders user specific Malamute settings on the user page
 * @param {WP_User} $user - The current user object of the profile
 * @return {void}
 */
function render_user_settings($user) {

	$default_theme = get_setting('malamute-editor-theme');
	
	$codemirror_themes = codemirror_get_themes();
	$theme_options = "";

	foreach ( $codemirror_themes as $theme ) {
		$cl = ($default_theme == $theme['name'] ? ' selected="selected"' : '');
		$theme_options .= sprintf('<option%s value="%s">%s</option>', $cl, $theme['name'], $theme['name']);
	}

	$buffer = sprintf('
		<h2>Malamute</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th>Editor theme</th>
					<td><select id="malamute-editor-theme" name="malamute-editor-theme">%s</select></td>
				</tr>
			</tbody>
		</table>
	', $theme_options);

	echo $buffer;
}
add_action('show_user_profile', '\malamute\render_user_settings');
add_action('edit_user_profile', '\malamute\render_user_settings');

/**
 * Processes and saves user specific Malamute settings when a user saves their profile
 * @param {int} $user_id - The ID of the user who is making profile changes
 * @return {void}
 */
function process_user_settings($user_id) {
	if ( current_user_can('edit_user', $user_id) ) {
		$editor_theme = $_POST['malamute-editor-theme'];
		if ( codemirror_theme_exists($editor_theme) ) {
			update_user_meta($user_id, 'malamute-editor-theme', $editor_theme);
		}
	}
}
add_action('personal_options_update', '\malamute\process_user_settings');
add_action('edit_user_profile_update', '\malamute\process_user_settings');

/**
 * Returns a relevant setting (or it's default value)
 * @param {string} $setting_name - The setting name
 * @return {mixed}
 */
function get_setting($setting_name) {

	$user = wp_get_current_user();

	switch ( $setting_name ) {
		case "malamute-editor-theme":
			$meta = get_user_meta($user->ID, 'malamute-editor-theme', true);
			$theme = ($meta ? $meta : "base16-light");
			return $theme;
		break;
	}

}