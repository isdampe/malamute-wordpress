<?php defined('ABSPATH') or exit;
/*
 * Plugin Name: Malamute
 * Plugin URI:  https://developer.wordpress.org/plugins/malamute/
 * Description: Replace Wordpress content with markdown
 * Version:     0.0.0
 * Author:      isdampe
 * Author URI:  http://dam.pe/
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: malamute
*/

define('MALAMUTE_VERSION',						'0.1.0');

include 'config/codemirror-config.php';
include 'lib/hook.php';
include 'lib/parse.php';
include 'lib/codemirror.php';
include 'lib/settings.php';