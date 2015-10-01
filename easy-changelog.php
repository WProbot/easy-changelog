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

// Include classes
require plugin_dir_path( __FILE__ ) . 'includes/class-easychangelog-posttype.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-easychangelog-settings.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$changelog_settings  = new Easy_Changelog_Settings;

// Instantiate main plugin file, so activation callback does not need to be static.
$changelog_post_type = new Easy_Changelog_Post_Type_Registration(
	$changelog_settings
);

$changelog_post_type->init();

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
