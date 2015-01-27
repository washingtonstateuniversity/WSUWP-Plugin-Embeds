<?php
/*
Plugin Name: WSU Embeds
Version: 0.0.1
Plugin URI: http://web.wsu.edu
Description: Provides various embed codes supported on the WSUWP Platform.
Author: washingtonstateuniversity, jeremyfelt
Author URI: http://web.wsu.edu
*/

class WSUWP_Embeds {
	public function __construct() {
		add_shortcode( 'qualtrics', array( $this, 'display_qualtrics_shortcode' ) );
		add_shortcode( 'qualtrics_multi', array( $this, 'display_qualtrics_multi_shortcode' ) );
		add_action( 'wp_head', array( $this, 'handle_qualtrics_multi_shortcode' ) );
	}

	/**
	 * Embed the iframe requested through the Qualtrics shortcode.
	 *
	 * @param array $atts List of attributes passed with the shortcode.
	 *
	 * @return string HTML to output.
	 */
	public function display_qualtrics_shortcode( $atts ) {
		$default_atts = array(
			'url' => '',
			'width' => '100%',
			'height' => '100%',
			'min_height' => '400px',
		);
		$atts = shortcode_atts( $default_atts, $atts );

		preg_match( '/https\:\/\/(.+?)\.qualtrics\.com\/(.+)/i', $atts['url'], $matches );

		if ( 3 !== count( $matches ) ) {
			return '';
		}

		$html = '<iframe class="qualtrics-embed" src="https://' . esc_attr( $matches[1] ) . '.qualtrics.com/' . esc_attr( $matches[2] ) . '" ';
		$html .= 'name="Qualtrics" scrolling="auto" frameborder="no" align="center" ';
		$html .= 'style="width: ' . esc_attr( $atts['width'] ) . ';height:' . esc_attr( $atts['height'] ) . ';min-height:' . esc_attr( $atts['min_height'] ) . ';"></iframe>';

		return $html;
	}

	/**
	 * Provide a method to randomly redirect the user to up to 5 different Qualtrics surveys.
	 *
	 * @return string Empty text, as we output the `script` tag directly.
	 */
	public function display_qualtrics_multi_shortcode( $atts ) {
		$default_atts = array(
			'url1' => '',
			'url2' => '',
			'url3' => '',
			'url4' => '',
			'url5' => '',
		);
		$atts = shortcode_atts( $default_atts, $atts );

		$urls = array();
		foreach( $atts as $key => $url ) {
			if ( preg_match( '/https\:\/\/(.+?)\.qualtrics\.com\/(.+)/i', $url ) ) {
				$urls[] = $url;
			}
		}

		$count = count( $urls );

		$html = '<script type="application/javascript">';
		$html .= 'var sites = new Array(' . $count . ');';

		for( $i = 0; $i < $count; $i++ ) {
			$html .= 'sites[' . $i . '] = "' . esc_url( $urls[ $i ] ) . '";';
		}

		$html .= 'var rnd = Math.floor(Math.random() * ' . $count . ');';
		$html .= 'window.location.href = sites[rnd];';
		$html .= '</script>';

		echo $html;
		return '';
	}

	/**
	 * If a single page has the `qualtrics_multi` shortcode, fire that shortcode early as we
	 * require some manual `script` tags in the header.
	 */
	public function handle_qualtrics_multi_shortcode() {
		global $post;
		if ( ! is_home() && ! is_front_page() && is_singular( 'page' ) && has_shortcode( $post->post_content, 'qualtrics_multi' ) ) {
			do_shortcode( $post->post_content );
			die();
		}
	}
}
new WSUWP_Embeds();