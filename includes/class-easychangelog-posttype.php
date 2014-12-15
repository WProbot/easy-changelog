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

class Easy_Changelog_Post_Type_Registration {

	public $post_type = 'changelog';

	public $taxonomies = 'project';

	public function init() {
		add_action( 'init', array( $this, 'register' ) );

		add_filter( 'the_content', array( $this, 'changelog_loop' ), 50 );
	}

	/**
	 * Initiate registrations of Changelog post types
	 *
	 * @since  1.0.0
	 */
	public function register() {
		$this->register_post_type_changelog();
		$this->register_taxonomy_type();
	}

	/**
	 * Register the Changelog type
	 *
	 * @since  1.0.0
	 */
	protected function register_post_type_changelog() {
		$labels = array(
			'name'               => __( 'Changelogs', 'easy-changelog' ),
			'singular_name'      => __( 'Changelog', 'easy-changelog' ),
			'menu_name'          => __( 'Changelogs', 'easy-changelog' ),
			'parent_item_colon'  => __( 'Parent Changelog:', 'easy-changelog' ),
			'all_items'          => __( 'All Changelogs', 'easy-changelog' ),
			'view_item'          => __( 'View Changelog', 'easy-changelog' ),
			'add_new_item'       => __( 'Add New Changelog', 'easy-changelog' ),
			'add_new'            => __( 'New Changelog', 'easy-changelog' ),
			'edit_item'          => __( 'Edit Changelog', 'easy-changelog' ),
			'update_item'        => __( 'Update Changelog', 'easy-changelog' ),
			'search_items'       => __( 'Search Changelogs', 'easy-changelog' ),
			'not_found'          => __( 'No Changelog found', 'easy-changelog' ),
			'not_found_in_trash' => __( 'No Changelog found in Trash', 'easy-changelog' ),
		);
		$rewrite = array(
			'slug'               => 'changelog',
			'with_front'         => true,
			'pages'              => true,
			'feeds'              => true,
		);
		$args = array(
			'label'              => __( 'Changelog', 'easy-changelog' ),
			'description'        => __( 'Changelog Information', 'easy-changelog' ),
			'labels'             => $labels,
			'supports'           => array( 'title', 'editor' ),
			'hierarchical'       => false,
			'menu_icon'          => 'dashicons-list-view',
			'menu_position'      => 20,
			'public'             => false,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_admin_bar'  => true,
			'can_export'         => true,
			'has_archive'        => false,
			'query_var'          => 'changelog',
			'rewrite'            => $rewrite,
			'capability_type'    => 'post',
		);

		$args = apply_filters( 'changelogposttype_args', $args );

		register_post_type( $this->post_type, $args );
	}

	/**
	 * register Project taxonomy
	 * @return taxonomy taxonomy to classify changelogs
	 *
	 * @since  1.0.0
	 */
	protected function register_taxonomy_type() {
		$labels = array(
			'name'         => __( 'Projects', 'easy-changelog' ),
			'add_new_item' => __( 'Add New Project', 'easy-changelog' ),
		);

		$args = array(
			'labels'            => $labels,
			'public'            => false,
			'show_in_nav_menus' => false,
			'show_ui'           => true,
			'show_tagcloud'     => false,
			'hierarchical'      => true,
			'rewrite'           => false,
			'show_admin_column' => true,
			'query_var'         => true,
		);

		$args = apply_filters( 'changelogtype_taxonomy_args', $args );

		register_taxonomy( $this->taxonomies, $this->post_type, $args );

	}

	/**
	 * loop of changelog posts
	 * @param  filter $content add new loop
	 * @return content          add all posts in specified Project taxonomy
	 *
	 * @since  1.0.0
	 */
	public function changelog_loop( $content ) {

		$easy      = get_option( 'easychangelog' );
		$post_type = 'page';
		if ( $easy['post_type'] ) {
			$post_type = esc_attr( $easy['post_type'] );
		}

		$page  = get_post();
		$slug  = $page->post_name;
		$terms = get_terms( 'project' );

		$term_list = array();
		foreach ( $terms as $term ) {
			$term_list[] .= $term->slug;
		}

		if ( ! is_singular( $post_type ) || ! in_array( $slug, $term_list ) ) {
			return $content;
		}

		$args = array(
			'nopaging'       => 'true',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_type'      => 'changelog',
			'project'        => $slug
		);

		//* The Query
		$the_query = new WP_Query( $args );

		$heading = __( 'Changelog', 'easy-changelog' );
		if ( $easy['heading'] ) {
			$heading = $easy['heading'];
		}

		//* The Loop
		if ( $the_query->have_posts() ) {
			$content .= '<h2>' . esc_html( $heading ) . '</h2>';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$content .= '<div class="changelog-entry">';
				$content .= '<h3>' . esc_html( get_the_title() ) . '</h3>';
				$content .= wpautop( get_the_content() );
				$content .= '<div class="changelog-meta">' . __( 'Added: ',  'easy-changelog' ) . get_the_date() . '</div>';
				$content .= '</div>';
			}
		}

		//* Restore original Post Data
		wp_reset_postdata();

		return $content;
	}

}
