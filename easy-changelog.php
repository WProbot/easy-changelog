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
require plugin_dir_path( __FILE__ ) . 'includes/class-easychangelog-registration.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-easychangelog-settings.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$changelog_post_type_registrations = new Easy_Changelog_Post_Type_Registration;
$changelog_settings  = new Easy_Changelog_Settings;

// Instantiate main plugin file, so activation callback does not need to be static.
$changelog_post_type = new Easy_Changelog_Custom_Post_Types(
	$changelog_post_type_registrations,
	$changelog_settings
);

// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $changelog_post_type, 'activate' ) );

// Initialise registrations for post-activation requests.
$changelog_post_type_registrations->init();
