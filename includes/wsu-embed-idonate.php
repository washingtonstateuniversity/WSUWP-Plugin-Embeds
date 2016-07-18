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
		);
		$atts = shortcode_atts( $defaults, $atts );

		$atts['id'] = sanitize_key( $atts['id'] );

		if ( empty( $atts['id'] ) ) {
			return '';
		}

		wp_enqueue_script( 'wsu-idonate', 'https://staging-embed.idonate.com/idonate.js', array(), false, true );

		return '<div data-idonate-embed="' . $atts['id'] . '"></div>';
	}
}
new WSUWP_Embed_Idonate();
