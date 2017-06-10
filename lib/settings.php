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

/**
 * Registers the global settings page with the Wordpress core
 * @return {void}
 */
function register_settings_page() {

	add_submenu_page('options-general.php', 'Malamute', 'Malamute', 'manage_options', 'malamute-options', '\malamute\render_settings_page');

}
add_action('admin_menu', '\malamute\register_settings_page');

/**
 * Registers settings with Wordpress core
 * @return {void}
 */
function register_settings() {

	register_setting('malamute-settings-group', 'malamute-codemirror_active_modes');

}
add_action('admin_init', '\malamute\register_settings');

/**
 * Renders the global settings page
 * @return {void}
 */
function render_settings_page() {

	$mode_options = '<div style="padding-top: 15px;">';
	$codemirror_modes = codemirror_get_modes();
	foreach ( $codemirror_modes as $mode ) {
		$checked = "";
		$mode_options .= sprintf('<div style="margin-bottom: 10px; float: left; width: 180px;"><label for="%s"><input data-hook="malamute-set-codemirror-mode" type="checkbox" name="%s" id="%s" %s /> %s</label></div>',
							$mode['name'],
							$mode['name'],
							$mode['name'],
							$checked,
							ucfirst($mode['name'])
						);
	}
	$mode_options .= '</div>';

	$buffer = sprintf('
	<div class="wrap">
		<h1>Malamute Settings</h1>
		<form method="post" action="options.php">

			<input type="hidden" name="option_page" value="malamute-settings-group" />
			<input type="hidden" name="action" value="update" />
			%s
			<input type="hidden" id="malamute-codemirror_active_modes" name="malamute-codemirror_active_modes" />

			<table class="form-table">
				<tbody>
					<tr valign="top">
						<th scope="row">Enabled CodeMirror modes</th>
						<td style="padding-top: 25px;">
							<div style="margin-bottom: 15px;"><input id="malamute-check-all" type="checkbox" /> Select all</div>
							<hr>
							%s
						</td>
					</tr>
				</tbody>
			</table>

			<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"  /></p>

		</form>
	</div>', wp_nonce_field('malamute-settings-group', '_wpnonce', true, false), $mode_options);

	echo $buffer;

}

/**
 * Adds options script to the WP script queue for admin users
 * @param {string} $hook - The Wordpress hook identifier
 * @return {void}
 */
function enqueue_options_script($hook) {

	if ( $hook == 'settings_page_malamute-options' ) {
		wp_enqueue_script('malamute-js-options', plugins_url('malamute/assets/js/malamute-options.js'));
	}

}
add_action('admin_enqueue_scripts', '\malamute\enqueue_options_script');