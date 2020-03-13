<?php

class Shortcode_WSUWP_Feed {

	protected static $shortcode = 'wsuwp_feed';
	protected static $default_atts = array(
		'post_type'           => 'post',
		'category'            => '',
		'title_tag'           => 'h2',
		'relation'            => 'AND',
		'count'               => 10,
		'show_pagination'     => '1',
		'display'             => 'full',
		'show_post_title'     => '1',
		'show_image'          => '1',
		'show_post_meta'      => 'date,author',
		'wpautop'             => '',
		'categories'          => '',
		'format'              => '',
		'show_toc'            => '',
		'exclude'             => '',
		'orderby'             => 'date',
		'order'               => 'DESC',
	);


	public function init() {

		add_shortcode( 'wsuwp_feed', __CLASS__ . '::render_shortcode' );

	}


	public static function render_shortcode( $atts ) {

		$content = '';

		$content .= '<div class="wsu-c-wsuwp-feed__wrapper">';

		$atts = shortcode_atts( self::$default_atts, $atts, self::$shortcode );

		$atts['show_post_meta'] = explode( ',', $atts['show_post_meta'] );

		$query_args = self::get_query( $atts );

		switch ( $atts['format'] ) {

			case 'by-category':
				$content .= self::get_posts_by_category( $query_args, $atts );
				break;
			default:
				$content .= self::get_posts( $query_args, $atts );
				break;
		}

		$content .= '</div>';

		return $content;

	}


	protected static function get_posts( $query_args, $atts ) {

		$content = '';

		$the_query = new \WP_Query( $query_args );

		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {

				$the_query->the_post();

				ob_start();

				switch ( $atts['display'] ) {
					case 'full':
						include WSUWP_Embeds::get_template_path() . '/wsuwp-feed/full.php';
						break;
					case 'button-excerpts':
						include WSUWP_Embeds::get_template_path() . '/wsuwp-feed/button-excerpts.php';
						break;
					case 'titles':
						include WSUWP_Embeds::get_template_path() . '/wsuwp-feed/titles.php';
						break;
				}

				$content .= ob_get_clean();
			}

		}

		/* Restore original Post Data */
		wp_reset_postdata();

		return $content;

	}

	protected static function get_posts_by_category( $query_args, $atts ) {

		$content = '';

		$categories = self::get_categories( $atts );

		$category_posts = array();

		$toc = '';


		// Remove any set categories.
		unset( $query_args['cat'] );

		foreach ( $categories as $index => $category ) {

			$category_posts = self::get_category_posts( $category['id'], $query_args, $atts );

			$categories[ $index ]['posts'] = $category_posts;

			if ( ! empty( $category_posts ) ) {

				$content .= '<h2 class="wsu-c-wp_feed__section__title">' . $category['name'] . '</h2><div class="wsu-c-wp_feed__section__wrapper">';

				$toc .= '<h2 class="wsu-c-wp_feed__toc__title">' . $category['name'] . '</h2><ul>';

				foreach ( $category_posts as $post ) {

					$content .= $post['content'];

					$toc .= '<li class="wsu-c-wp_feed__toc__item"><a href="#post-' . $post['id'] . '">' . $post['title'] . '</a></li>';

				}

				$toc .= '</ul>';

				$content .= '</div>';
			}

			if ( ! empty( $category['children'] ) ) {

				foreach( $category['children'] as $child_index => $child ) {

					$child_category_posts = self::get_category_posts( $child['id'], $query_args, $atts );

					$categories[ $index ]['children'][ $child_index ]['posts'] = $child_category_posts;

					if ( ! empty( $child_category_posts ) ) {

						$content .= '<h2 class="wsu-c-wp_feed__section__title">' . $child['name'] . '</h2><div class="wsu-c-wp_feed__section__wrapper">';

						$toc .= '<h2 class="wsu-c-wp_feed__toc__title">' . $child['name'] . '</h2><ul>';

						foreach ( $child_category_posts as $child_post ) {

							$content .= $child_post['content'];

							$toc .= '<li class="wsu-c-wp_feed__toc__item"><a class="wsu-c-wp_feed__toc__item" href="#post-' . $child_post['id'] . '">' . $child_post['title'] . '</a></li>';

						}

						$toc .= '</ul>';

						$content .= '</div>';
					}
				}
			}
		}

		if ( ! empty( $atts['show_toc'] ) ) {

			$content = '<div class="wsu-c-wp_feed__toc__wrapper">' . $toc . '</div>' . $content;

		}

		return $content;

	}


	protected static function get_category_posts( $category_id, $query_args, $atts ) {

		$posts = array();

		$query_args['category__in'] = array( $category_id );

		$the_query = new \WP_Query( $query_args );

		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {

				$the_query->the_post();

				ob_start();

				switch ( $atts['display'] ) {
					case 'full':
						include WSUWP_Embeds::get_template_path() . '/wsuwp-feed/full.php';
						break;
					case 'titles':
						include WSUWP_Embeds::get_template_path() . '/wsuwp-feed/titles.php';
						break;
				}

				$posts[] = array(
					'id'      => get_the_ID(),
					'title'   => get_the_title(),
					'content' => ob_get_clean(),
					'slug'    => $the_query->post->post_name,
					'link'    => get_the_permalink(),
				);
			}
		}

		/* Restore original Post Data */
		wp_reset_postdata();

		return $posts;

	}


	protected static function get_categories( $atts ) {

		$categories = array();

		$category_ids = explode( ',', $atts['categories'] );

		foreach ( $category_ids as $category_id ) {

			$wp_term = get_term( $category_id, 'category' );

			$term = array(
				'id' => $wp_term->term_id,
				'name' => $wp_term->name,
				'slug' => $wp_term->slug,
				'term' => $wp_term,
				'children' => array(),
			);

			$child_ids = get_term_children( $category_id, 'category' );

			if ( ! empty( $child_ids ) && is_array( $child_ids ) ) {

				foreach ( $child_ids as $child_id ) {

					$wp_child_term = get_term( $child_id, 'category' );
					$child_term = array(
						'id' => $wp_child_term->term_id,
						'name' => $wp_child_term->name,
						'slug' => $wp_child_term->slug,
						'term' => $wp_child_term,
					);

					$term['children'][] = $child_term;
				}
			}

			$categories[] = $term;

		}

		return $categories;

	}


	protected static function get_query( $atts ) {

		$query = array(
			'post_type'      => ( ! empty( $atts['post_type'] ) ) ? $atts['post_type'] : 'post',
			'posts_per_page' => ( ! empty( $atts['count'] ) ) ? $atts['count'] : 10,
			'post_status'    => 'publish',
			'orderby'        => ( ! empty( $atts['orderby'] ) ) ? $atts['orderby'] : 'date',
			'order'          => ( ! empty( $atts['order'] ) ) ? $atts['order'] : 'DESC',
		);

		if ( ! empty( $atts['categories'] ) ) {

			$query['cat'] = $atts['categories'];

		}

		return $query;

	}
}

(new Shortcode_WSUWP_Feed )->init();
