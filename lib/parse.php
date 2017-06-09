<?php

namespace malamute;

defined('ABSPATH') or exit;

include dirname(__FILE__) . '/../vendor/parsedown/Parsedown.php';

/**
 * Removes the default wordpress the_content filters
 * This is essential to allow proper markdown parsing
 * @param {string} $content - The post content
 * @return {void}
 */
function remove_autop( $content ) {
	
	remove_filter('the_content', 'wpautop');
	remove_filter('the_content', 'wptexturize');
	remove_filter('the_content', 'prepend_attachment');
	remove_filter('the_content', 'wp_make_content_images_responsive');
	remove_filter('the_content', 'shortcode_unautop');
	
}
add_action('init', '\malamute\remove_autop');

/**
 * Filters raw post content (typically markdown in our case)
 * and produces nice HTML for the front end
 * @param {string} $content - The HTML string content to parse
 * @return {string} - The parsed markdown as HTML
 */
function parse_post($content) {
	$Parsedown = new \Parsedown();
	$content = $Parsedown->text( $content );
	return $content;
}
add_filter('the_content', '\malamute\parse_post');