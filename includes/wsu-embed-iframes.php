<?php

class WSUWP_Embed_Iframes {

	/**
	 * WSUWP_Embed_Iframes constructor.
	 *
	 * @since 0.12.2
	 */
	public function __construct() {
		add_shortcode( 'wsuwp_iframe', array( $this, 'display_wsuwp_iframe' ) );
	}

	/**
	 * Display the iframe.
	 *
	 * @since 0.12.2
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function display_wsuwp_iframe( $atts ) {
		$defaults = array(
			'src' => '',
			'title' => '',
			'width' => '800',
			'height' => '600',
			'responsive' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		// Bail if the `src` attribute is empty.
		if ( empty( $atts['src'] ) ) {
			return '<!-- The wsuwp_iframe shortcode must contain a "src" value. -->';
		}

		// Bail if the `title` attribute is empty.
		if ( empty( $atts['title'] ) ) {
			return '<!-- The wsuwp_iframe shortcode must contain a "title" value. -->';
		}

		// Parse the `src` attribute value.
		$url = wp_parse_url( $atts['src'] );

		// Bail if the
		if ( ! $url ) {
			return '<!-- The wsuwp_iframe shortcode must contain a valid "src" value. -->';
		}

		// Define the list of hosts from which iframe embeds are allowed.
		$allowed_hosts = array(
			'emailwsu.sharepoint.com',
			'chaselab.net',
			'fast.wistia.net'
		);

		// Bail if the host of the `src` attribute value isn't allowed.
		if ( ! in_array( $url['host'], $allowed_hosts, true ) ) {
			return '<!-- The wsuwp_iframe shortcode does not allow embeds from ' . esc_html( $url['host'] ) . '. -->';
		}

		// "Endcode" any curly brackets if the URL is from Sharepoint...
		if ( 'emailwsu.sharepoint.com' === $url['host'] ) {
			$atts['src'] = str_replace( array( '{', '}' ), array( '%7B', '%7D' ), $atts['src'] );
		}

		ob_start();

		if ( ! empty( $atts['responsive'] ) ) {
			$ratio = ( absint( $atts['height'] ) / absint( $atts['width'] ) ) * 100;
			$padding_bottom = number_format_i18n( $ratio, 2 ); // Allow up to two decimal points.
			?>
			<div class="wsuwp-embed-responsive-container"
				 style="overflow:hidden;padding-bottom:<?php echo esc_html( $padding_bottom ); ?>%;position:relative;">
			<?php
		}

		?>
		<iframe src="<?php echo esc_url( $atts['src'] ); ?>"
				<?php if ( ! empty( $atts['responsive'] ) ) { ?>
				style="height:100%;left:0;position:absolute;top:0;width:100%;"
				<?php } else { ?>
				width="<?php echo esc_attr( $atts['width'] ); ?>"
				height="<?php echo esc_attr( $atts['height'] ); ?>"
				<?php } ?>
				title="<?php echo esc_attr( $atts['title'] ); ?>">This is an embedded <a href="https://office.com">Microsoft Office</a> document, powered by <a href="https://office.com/webapps">Office Online</a>.</iframe>
		<?php

		if ( ! empty( $atts['responsive'] ) ) {
			?></div><?php
		}

		$content = ob_get_clean();

		return $content;
	}
}

new WSUWP_Embed_Iframes();
