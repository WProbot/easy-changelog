<?php

/**
 *
 * RGC Changelog
 *
 * @package    RGC_Changelog
 * @author     Robin Cornett <hello@robincornett.com>
 * @copyright  2014 Robin Cornett
 *
 */

class EasyChangelog {

	public function __construct( $post_type, $output, $settings ) {
		$this->post_type = $post_type;
		$this->output    = $output;
		$this->settings  = $settings;
	}

	public function init() {
		add_action( 'init', array( $this->post_type, 'register' ) );
		add_filter( 'easychangelog_print_log', array( $this->output, 'do_changelog' ) );
		add_action( 'admin_menu', array( $this->settings, 'do_submenu_page' ) );
	}
}
