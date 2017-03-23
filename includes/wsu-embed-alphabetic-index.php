<?php

class WSUWP_Embed_Alphabetic_Index {
	public function __construct() {
		add_shortcode( 'wsu_alphabetic_index', array( $this, 'display_wsu_alphabetic_index' ) );
	}

	public function display_wsu_alphabetic_index( $atts ) {
		$defaults = array(
			'site_category_slug' => '',
			'header_level' => 'h2',
		);

		$atts = shortcode_atts( $defaults, $atts );

		if ( empty( $atts['site_category_slug'] ) ) {
			return '';
		}

		if ( in_array( $atts['header_level'], array( 'h2', 'h3', 'h4' ), true ) ) {
			$header = $atts['header_level'];
		} else {
			$header = 'h2';
		}

		wp_enqueue_script( 'wsuwp-alpha-index', plugins_url( '/js/wsuwp-alpha-index.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true );

		// Get the most recent post in the given category.
		$cache_query_args = array(
			'posts_per_page' => 1,
			'category_name' => sanitize_key( $atts['site_category_slug'] ),
		);

		$cache_query = new WP_Query( $cache_query_args );

		if ( $cache_query->have_posts() ) {
			while ( $cache_query->have_posts() ) {
				$cache_query->the_post();

				// Add the most recent post's ID to the atts array.
				// This will be used as the cache key - if a new post is
				// published or attributes change, it will break the cache.
				$atts['newest_post'] = get_the_ID();
			}
			wp_reset_postdata();
		}

		$cache_key = md5( wp_json_encode( $atts ) );

		$cached_content = wp_cache_get( $cache_key, 'wsu_alphabetic_index' );

		if ( $cached_content ) {
			return $cached_content;
		}

		// Query for all the posts in the given category.
		// Sorting by name is more reliable (as titles can have weird characters),
		// but it can also yield unexpected results.
		$query_args = array(
			'posts_per_page' => -1,
			'category_name' => sanitize_key( $atts['site_category_slug'] ),
			'orderby' => 'name',
			'order' => 'asc',
		);

		$query = new WP_Query( $query_args );

		ob_start();

		// Build the menu of indexes.
		if ( $query->have_posts() ) {

			$current_index = '';

			?>
			<ul class="index">
			<?php

			while ( $query->have_posts() ) {
				$query->the_post();

				$post_index = sanitize_title( get_the_title() )[0];

				if ( $post_index !== $current_index ) {
					?><li><a href="#<?php echo esc_attr( $post_index ); ?>"><?php echo esc_html( strtoupper( $post_index ) ); ?></a></li><?php
					$current_index = $post_index;
				}
			}

			?>
			</ul>
			<?php

			wp_reset_postdata();
		}

		$query->rewind_posts();

		// Build the post groups.
		if ( $query->have_posts() ) {

			$current_index = '';

			while ( $query->have_posts() ) {
				$query->the_post();

				$post_index = sanitize_title( get_the_title() )[0];

				if ( $post_index !== $current_index ) {

					if ( '' !== $current_index ) { ?></ul><?php } ?>

					<<?php echo esc_html( $header ); ?> id="<?php echo esc_attr( $post_index ); ?>"><?php echo esc_html( strtoupper( $post_index ) ); ?></<?php echo esc_html( $header ); ?>>

					<ul class="group">
					<?php
					$current_index = $post_index;
				}
				?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php
			}

			?></ul><?php

			wp_reset_postdata();
		}

		$content = ob_get_clean();

		wp_cache_set( $cache_key, $content, 'wsu_alphabetic_index' );

		return $content;
	}
}
new WSUWP_Embed_Alphabetic_Index();
