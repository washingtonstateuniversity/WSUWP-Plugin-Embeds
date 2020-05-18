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
			'ids'  => '',
			'name' => ''
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( ! empty( $atts['ids'] ) ) {

			$ids  = explode(',', $atts['ids']);
			$name = ($atts['name'] !== '')? $atts['name'] : 'page-id-' . get_the_ID();

			ob_start();

			include dirname( dirname( __FILE__ ) ) . '/displays/photo-carousel.php';

			return ob_get_clean();

		} else {
			echo "<code>Error: Shortcode requires you pass at least one <strong>ids</strong>.</code>";
		}

	}

}

$wsu_photo_carousel = new WSUWP_Embed_Photo_Carousel();
