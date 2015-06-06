<?php
/**
 * Class WSUWP_Embed_Uchat
 */
class WSUWP_Embed_Uchat {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_shortcode( 'wsu_uchat', array( $this, 'display_wsu_uchat' ) );
	}

	/**
	 * Display the script used to provide a UChat interface. If an ID is not provided,
	 * not script will be output.
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function display_wsu_uchat( $atts ) {
		$defaults = array(
			'id' => '',
		);
		$atts = shortcode_atts( $defaults, $atts );

		if ( empty ( $atts['id'] ) ) {
			return '';
		}

		ob_start();
		?>
		<script type="text/javascript" src="//uchat.co/widget.js?school=<?php echo esc_attr( $atts['id'] ); ?>"></script>
		<?php

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
new WSUWP_Embed_Uchat();