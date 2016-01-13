<?php
/**
 * Class WSUWP_Embed_Countdown
 *
 * Provides a `wsuwp_countdown` shortcode to insert a text countdown in a page via countdown.js
 */
class WSUWP_Embed_Countdown {
	public function __construct() {
		add_shortcode( 'wsuwp_countdown', array( $this, 'display_wsuwp_countdown' ) );
	}

	public function display_wsuwp_countdown( $atts ) {
		$defaults = array(
			'date' => 'January 1, 2500 22:00:00 UTC',
			'wrapper' => 'strong',
		);
		$atts = shortcode_atts( $defaults, $atts );

		wp_enqueue_script( 'wsuwp-countdown', plugins_url( '/js/wsuwp-countdown.js', dirname( __FILE__ ) ), array( 'jquery' ), false, true );

		$data = array(
			'date' => esc_js( $atts['date'] ),
			'wrapper' => sanitize_key( $atts['wrapper'] ),
		);
		wp_localize_script( 'wsuwp-countdown', 'wsuwp_counter', $data );

		return '<span id="wsuwp-counter"></span>';
	}
}
new WSUWP_Embed_Countdown();