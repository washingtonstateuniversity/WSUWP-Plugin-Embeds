<?php

class WSUWP_Embed_Photo_Carousel {

	public function __construct() {
		add_shortcode( 'wsuwp_photo_carousel', array( $this, 'display_wsuwp_photo_carousel' ) );
		add_action( 'wp_enqueue_scripts', 'WSUWP_Embed_Photo_Carousel::register_scripts' );
	}

	static public function register_scripts() {
		wp_register_style( 'wsuwp-embed-photo-carousel-styles', WSUWP_Embeds::get_plugin_url() . 'css/swiper.min.css' , array(), '5.4.0' );
		wp_register_script( 'wsuwp-embed-photo-carousel-scripts', WSUWP_Embeds::get_plugin_url() . 'js/swiper.min.js', array(), '5.4.0', true );
	}

	public function display_wsuwp_photo_carousel( $atts, $content, $tag ) {

		// Load Deps
		wp_enqueue_style('wsuwp-embed-photo-carousel-styles');
		wp_enqueue_script('wsuwp-embed-photo-carousel-scripts');

		// Set Defaults
		$default_atts = array(
			'ids'                     => '',
			'name'                    => '',
			'image_size'              => 'medium',
			'random_order'            => true,
			'slides_per_view'         => '3',
			'slides_per_column'       => '2',
			'space_between'           => '20',
			'preload_images'          => 'false', // JS booleans must be strings to work with this method
			'lazy'                    => 'true', // JS booleans must be strings to work with this method
			'watch_slides_visibility' => 'true', // JS booleans must be strings to work with this method
			'download_image_on_click' => false,
			'download_image_size'     => 'full',
			'pagination_type'         => 'bullets'
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( ! empty( $atts['ids'] ) ) {

			//
			// Set Vars
			//
			$ids  = explode(',', $atts['ids']);
			$name = ($atts['name'] !== '')? $atts['name'] : 'page-id-' . get_the_ID();
			$image_size = $atts['image_size'];
			$random_order = $atts['random_order'];
			$slides_per_view = $atts['slides_per_view'];
			$slides_per_column = $atts['slides_per_column'];
			$space_between = $atts['space_between'];
			$preload_images = $atts['preload_images'];
			$lazy = $atts['lazy'];
			$watch_slides_visibility = $atts['watch_slides_visibility'];
			$download_image_on_click = $atts['download_image_on_click'];
			$download_image_size = $atts['download_image_size'];
			$pagination_type = $atts['pagination_type'];

			//
			// Process any Vars as needed
			//

			// Shuffle array if random_order is desired
			if ($random_order) {
				shuffle($ids);
			}

			// Preload images if lazy loading is disabled
			if ( $lazy == 'false' ) {
				$preload_images = 'true'; // JS booleans must be strings to work with this method
			}

			// Slides with multiple columns require that we watch for slides visibility
			if ( $slides_per_column <= '1') {
				$watch_slides_visibility = 'false'; // JS booleans must be strings to work with this method
			}

			// Strip out px if passed into value, only allowed to pass px values
			if ( stripos($space_between, 'px') !== false){
				$space_between = rtrim($space_between, 'px');
			}

			//
			// Begin Output
			//
			ob_start();

			include dirname( dirname( __FILE__ ) ) . '/displays/photo-carousel.php';

			return ob_get_clean();

		} else {
			echo "<code>Error: Shortcode requires you pass at least one <strong>ids</strong>.</code>";
		}

	}

}

$wsu_photo_carousel = new WSUWP_Embed_Photo_Carousel();
