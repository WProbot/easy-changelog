<?php
/**
 * @package   easychangelog
 * @author    Robin Cornett <hello@robincornett.com>
 * @license   GPL-2.0+
 * @link      http://robincornett.com
 * @copyright 2014 Robin Cornett Creative, LLC
 */

class Easy_Changelog_Settings {

	/**
	 * variable set for easy changelog option
	 * @var option
	 */
	protected $setting;

	function __construct() {
		add_action( 'admin_menu', array( $this, 'do_submenu_page' ) );
	}


	/**
	 * add a submenu page under Changelog Post Type
	 * @return submenu Easy Changelog settings page
	 * @since  1.0.0
	 */
	public function do_submenu_page() {

		add_submenu_page(
			'edit.php?post_type=changelog',
			__( 'Easy Changelog Settings', 'easy-changelog' ),
			__( 'Settings', 'easy-changelog' ),
			'manage_categories',
			'settings',
			array( $this, 'do_settings_form' )
		);

		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'load-changelog_page_settings', array( $this, 'help' ) );

	}

	/**
	 * create settings form
	 * @return form Easy Changelog settings
	 *
	 * @since  1.0.0
	 */
	public function do_settings_form() {
		$page_title = get_admin_page_title();

		echo '<div class="wrap">';
			echo '<h2>' . $page_title . '</h2>';
			echo '<form action="options.php" method="post">';
				settings_fields( 'easychangelog' );
				do_settings_sections( 'easychangelog' );
				wp_nonce_field( 'easychangelog_save-settings', 'easychangelog_nonce', false );
				submit_button();
				settings_errors();
			echo '</form>';
		echo '</div>';
	}

	/**
	 * Settings for options screen
	 * @return settings for backstretch image options
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {

		register_setting( 'easychangelog', 'easychangelog', array( $this, 'do_validation_things' ) );

		$defaults = array(
			'heading'   => '',
			'post_type' => 'page'
		);

		$this->setting = get_option( 'easychangelog', $defaults );

		add_settings_section(
			'easy_changelog_section',
			__( 'Optional Sitewide Settings', 'easy-changelog' ),
			array( $this, 'section_description'),
			'easychangelog'
		);

		add_settings_field(
			'easychangelog[heading]',
			'<label for="easychangelog[heading]">' . __( 'Heading' , 'easy-changelog' ) . '</label>',
			array( $this, 'heading' ),
			'easychangelog',
			'easy_changelog_section'
		);

		add_settings_field(
			'easychangelog[post_type]',
			'<label for="easychangelog[post_type]">' . __( 'Post Type', 'easy-changelog' ) . '</label>',
			array( $this, 'post_type' ),
			'easychangelog',
			'easy_changelog_section'
		);

	}


	/**
	 * Section description
	 * @return section description
	 *
	 * @since 1.0.0
	 */
	public function section_description() {
		echo '<p>' . __( 'The Easy Changelog plugin has just a few optional settings. Check the Help tab for more information. ', 'easy-changelog' ) . '</p>';
	}

	/**
	 * Setting for optional heading
	 * @return text string
	 *
	 * @since 1.0.0
	 */
	public function heading() {

		echo '<input type="text" class="regular-text" id="easychangelog[heading]" name="easychangelog[heading]" value="' . esc_attr( $this->setting['heading'] ) . '" />';
		echo '<p class="description">' . __( 'If you do not put a value in here, the heading above your changelog posts will be Changelog.', 'easy-changelog' ) . '</p>';

	}

	/**
	 * Setting for post type
	 * @return post_type select which post type the changelogs will be attached to
	 *
	 * @since  1.0.0
	 */
	public function post_type() {

		$args = array(
			'public'   => true,
			'_builtin' => false,
		);
		$output         = 'names';
		$operator       = 'and';
		$post_type_list = get_post_types( $args, $output, $operator );

		//* Add posts to that post_type_list
		$post_type_list['page'] = 'page';
		$post_type_list['post'] = 'post';

		$easy = get_option( 'easychangelog' );
		if ( ! $easy ) {
			$easy['post_type'] = 'page';
		}

		echo '<select id="easychangelog[post_type]" name="easychangelog[post_type]" >';

			foreach ( $post_type_list as $post_type_item ) {
				echo '<option value="'. esc_attr( $post_type_item ) .'" ' . selected( esc_attr( $post_type_item ), $easy['post_type'] ) .'>'. esc_attr( $post_type_item ) .'</option>';
			}

		echo '</select>';

		echo '<p class="description">' . __( 'Select the post type to which you would like for your changelogs to be attached.', 'easy-changelog' ) . '</p>';
	}

	/**
	 * validate all inputs
	 * @param  string $new_value various settings
	 * @return string            text or post type
	 *
	 * @since  1.0.0
	 */
	public function do_validation_things( $new_value ) {

		if ( empty( $_POST['easychangelog_nonce'] ) ) {
			wp_die( __( 'Something unexpected happened. Please try again.', 'easy-changelog' ) );
		}

		check_admin_referer( 'easychangelog_save-settings', 'easychangelog_nonce' );

		$new_value['heading']   = esc_html( $new_value['heading'] );

		$new_value['post_type'] = esc_attr( $new_value['post_type'] );

		return $new_value;

	}

		/**
	 * Help tab for media screen
	 * @return help tab with verbose information for plugin
	 *
	 * @since 1.1.0
	 */
	public function help() {
		$screen = get_current_screen();

		$heading_help =
			'<h3>' . __( 'Heading', 'easy-changelog' ) . '</h3>' .
			'<p>' . __( 'If you want the heading above your Changelog posts to be something other than "Changelog", enter a new heading here.', 'easy-changelog' ) . '</p>';

		$post_type_help =
			'<h3>' . __( 'Post Type', 'easy-changelog' ) . '</h3>' .
			'<p>' . __( 'By default, Easy Changelog will attach your changelog posts to pages, but here, you may change which post type the plugin will use.', 'easy-changelog' ) . '</p>' .
			'<p>' . __( 'Please note that your Project slug must match the slug of the page (or post type) to which you want the changelog posts attached.', 'easy-changelog' ) . '</p>';

		$screen->add_help_tab( array(
			'id'      => 'displayfeaturedimage_less_header-help',
			'title'   => __( 'Heading', 'easy-changelog' ),
			'content' => $heading_help,
		) );

		$screen->add_help_tab( array(
			'id'      => 'displayfeaturedimage_default-help',
			'title'   => __( 'Post Type', 'easy-changelog' ),
			'content' => $post_type_help,
		) );

	}

}
