<?php
/**
 * Class WSUWP_Embed_Idonate
 */
class WSUWP_Embed_Idonate {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_shortcode( 'wsu_idonate', array( $this, 'display_wsu_idonate' ) );
	}

	public function display_wsu_idonate( $atts ) {
		$defaults = array(
			'id' => '',
			'server' => 'staging',
		);
		$atts = shortcode_atts( $defaults, $atts );

		$atts['id'] = sanitize_key( $atts['id'] );

		if ( empty( $atts['id'] ) ) {
			return '';
		}

		if ( 'production' === $atts['server'] ) {
			$url = 'https://embed.idonate.com/idonate.js';
		} else {
			$url = 'https://staging-embed.idonate.com/idonate.js';
		}

		wp_enqueue_script( 'wsu-idonate', $url, array(), false, true );

		return '<div data-idonate-embed="' . $atts['id'] . '"></div>';
	}
}
new WSUWP_Embed_Idonate();
