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

	/**
	 * loop of changelog posts
	 *
	 * @return mixed|string          add all posts in specified Project taxonomy
	 *
	 * @since  1.0.0
	 */
	public function do_changelog() {

		if ( ! $this->can_do() ) {
			return '';
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
			'tax_query'      => array(
				array(
					'taxonomy' => 'project',
					'field'    => 'slug',
					'terms'    => $slug,
				),
			),
		);

		// The Query
		$the_query = new WP_Query( $args );
		if ( ! $the_query->have_posts() ) {
			wp_reset_postdata();

			return '';
		}

		// The Loop
		$content  = '<details class="easychangelog">';
		$content .= '<summary><h2>' . $this->get_heading( $easy ) . '</h2></summary>';
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			$content .= '<div class="changelog-entry">';
			$content .= '<h3>' . esc_html( get_the_title() ) . '</h3>';
			$content .= wpautop( get_the_content() );
			$content .= '<div class="changelog-meta">' . __( 'Added: ', 'easy-changelog' ) . get_the_date() . '</div>';
			$content .= '</div>';
		}
		$content .= '</details>';

		// Restore original Post Data
		wp_reset_postdata();

		return $content;
	}

	/**
	 * Get the changelog heading.
	 *
	 * @param  array  $easy
	 * @return string
	 */
	private function get_heading( $easy ) {
		$heading = __( 'Changelog', 'easy-changelog' );
		if ( $easy['heading'] ) {
			$heading = $easy['heading'];
		}

		return apply_filters( 'easychangelog_heading', $heading );
	}

	/**
	 * Check if there is a changelog to be output
	 *
	 * @param  boolean $cando true if there is a changelog; false if not
	 *
	 * @return boolean
	 */
	protected function can_do( $cando = false ) {
		if ( ! is_singular() ) {
			return $cando;
		}
		$easy      = get_option( 'easychangelog' );
		$post_type = 'page';
		if ( $easy['post_type'] ) {
			$post_type = $easy['post_type'];
		}
		if ( ! is_singular( $post_type ) ) {
			return $cando;
		}
		$page  = get_post();
		$slug  = $page->post_name;
		$terms = get_terms( 'project' );

		$term_list = array();
		foreach ( $terms as $term ) {
			$term_list[] = $term->slug;
		}

		if ( in_array( $slug, $term_list, true ) ) {
			$cando = true;
		}

		return $cando;
	}
}
