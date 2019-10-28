<?php

class WSUWP_Embed_Power_BI {

	public function __construct() {
		add_shortcode( 'wsuwp_powerbi', array( $this, 'display_wsuwp_powerbi' ) );
	}


	public function display_wsuwp_powerbi( $atts, $content, $tag ) {

		$default_atts = array(
			'path'   => '',
			'height' => '800px',
			'width'  => '100%',
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( ! empty( $atts['path'] ) ) {

			$path   = $atts['path'];
			$height = $atts['height'];
			$width  = $atts['width'];

			ob_start();

			include dirname( dirname( __FILE__ ) ) . '/displays/powerbi-embed.php';

			return ob_get_clean();

		} // End if

	}

}

$wsu_powerbi = new WSUWP_Embed_Power_BI();
