<?php

class WSUWP_Feed {

	protected static $shortcode = 'wsuwp_feed';
	protected static $default_atts = array(
		'post_type'           => 'post',
		'category'            => '',
		'tag'                 => '',
		'relation'            => 'AND',
		'count'               => 10,
		'show_pagination'     => '1',
		'display'             => 'full',
		'show_post_title'     => '1',
		'show_image'          => '1',
		'show_post_meta'      => 'date,author',
		'wpautop'             => '',
	);

	public static function render_shortcode( $atts ) {

		$content = '';

		$atts = shortcode_atts( self::$default_atts, $atts, self::$shortcode );

		$atts['show_post_meta'] = explode( ',', $atts['show_post_meta'] );

		$query_args = self::get_query( $atts );

		$the_query = new \WP_Query( $query_args );

		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {

				$the_query->the_post();

				ob_start();

				switch ( $atts['display'] ) {
					case 'full':
						include WSUWP_Embeds::get_template_path() . '/wsuwp-feed-full.php';
						break;
				}

				$content .= ob_get_clean();
			}
		}

		/* Restore original Post Data */
		wp_reset_postdata();

		return $content;

	}


	protected static function get_query( $atts ) {

		$query = array(
			'post_type'      => ( ! empty( $atts['post_type'] ) ) ? $atts['post_type'] : 'post',
			'posts_per_page' => ( ! empty( $atts['count'] ) ) ? $atts['count'] : 10,
			'post_status'    => 'publish',
		);

		return $query;

	}
}
