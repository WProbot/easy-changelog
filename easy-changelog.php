<?php
/**
 * Custom changelog plugin
 *
 * @package RGC_Changelog
 * @author  Robin Cornett <hello@robincornett.com>
 * @license  GPL-2.0+
 * @link http://robincornett.com
 * @copyright 2014 Robin Cornett Creative, LLC
 *
 * Plugin Name:       Changelog Custom Post Type
 * Description:       Useful for tracking changelog of a plugin
 * Author:            Robin Cornett
 * Author URI:        http://robincornett.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

function easychangelog_require() {
	$files = array(
		'class-easychangelog',
		'class-easychangelog-output',
		'class-easychangelog-posttype',
		'class-easychangelog-settings',
	);

	foreach ( $files as $file ) {
		require plugin_dir_path( __FILE__ ) . 'includes/' . $file . '.php';
	}
}
easychangelog_require();


// Instantiate registration class, so we can add it as a dependency to main plugin class.
$changelog_post_type = new EasyChangelog_Post_Type;
$changelog_settings  = new EasyChangelog_Settings;
$changelog_output    = new EasyChangelog_Output;

// Instantiate main plugin file, so activation callback does not need to be static.
$easychangelog = new EasyChangelog(
	$changelog_post_type,
	$changelog_output,
	$changelog_settings
);

$easychangelog->init();

if ( ! function_exists( 'easychangelog_do_log' ) ) {
	function easychangelog_do_log() {
		return apply_filters( 'easychangelog_print_log', false );
	}
}

add_filter( 'the_content', 'easychangelog_append_log', 99 );
function easychangelog_append_log( $content ) {
	$easy = get_option( 'easychangelog' );
	if ( ! is_singular( $easy['post_type'] ) ) {
		return $content;
	}
	$buttons = easychangelog_do_log();
	return $content . $buttons;
}
