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

class EasyChangelog_Output {

	public function load_scripts() {
		$cando = apply_filters( 'easychangelog_force_accordion', (bool) $this->can_do() );
		if ( ! $cando ) {
			return;
		}
		$script = apply_filters( 'easychangelog_accordion_js', plugin_dir_url( __FILE__ ) . 'js/accordion.js' );
		wp_enqueue_script( 'easychangelog-accordion', $script, array( 'jquery', 'jquery-ui-accordion' ), false, false );
		wp_localize_script( 'easychangelog-accordion', 'easychangelogSelector', apply_filters( 'easychangelog_accordion_selector', 'h2' ) );
	}

	/**
	 * loop of changelog posts
	 * @param  filter $content add new loop
	 * @return content          add all posts in specified Project taxonomy
	 *
	 * @since  1.0.0
	 */
	public function do_changelog() {

		if ( ! $this->can_do() ) {
			return;
		}

		$easy = get_option( 'easychangelog' );
		$page = get_post();
		$slug = $page->post_name;
		$args = array(
			'nopaging'       => 'true',
			'posts_per_page' => 100,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'post_type'      => 'changelog',
			'project'        => $slug,
		);

		// The Query
		$the_query = new WP_Query( $args );

		$heading = __( 'Changelog', 'easy-changelog' );
		if ( $easy['heading'] ) {
			$heading = $easy['heading'];
		}

		// The Loop
		if ( $the_query->have_posts() ) {
			$content  = '<div class="easychangelog">';
			$content .= '<h2>' . esc_html( $heading ) . '</h2>';
			$content .= '<div class="ec-list">';
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$content .= '<h3>' . esc_html( get_the_title() ) . '</h3>';
				$content .= '<div class="changelog-entry">';
				$content .= wpautop( get_the_content() );
				$content .= '<div class="changelog-meta">' . __( 'Added: ',  'easy-changelog' ) . get_the_date() . '</div>';
				$content .= '</div>';
			}
			$content .= '</div></div>';
		}

		// Restore original Post Data
		wp_reset_postdata();

		return $content;
	}

	/**
	 * Check if there is a changelog to be output
	 * @param  boolean $cando true if there is a changelog; false if not
	 * @return boolean
	 */
	protected function can_do( $cando = false ) {
		if ( ! is_singular() ) {
			return;
		}
		$easy      = get_option( 'easychangelog' );
		$post_type = 'page';
		if ( $easy['post_type'] ) {
			$post_type = $easy['post_type'];
		}
		$page  = get_post();
		$slug  = $page->post_name;
		$terms = get_terms( 'project' );

		$term_list = array();
		foreach ( $terms as $term ) {
			$term_list[] = $term->slug;
		}

		if ( is_singular( $post_type ) && in_array( $slug, $term_list ) ) {
			$cando = true;
		}
		return $cando;
	}
}
