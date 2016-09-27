<?php
/*
Plugin Name: WSU Embeds
Version: 0.8.0
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-Embeds
Description: Provides various embed codes supported on the WSUWP Platform.
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
*/

include_once( __DIR__ . '/includes/wsu-embed-twitter.php' );
include_once( __DIR__ . '/includes/wsu-embed-uchat.php' );
include_once( __DIR__ . '/includes/wsu-embed-facebook-post.php' );
include_once( __DIR__ . '/includes/wsu-embed-codepen.php' );
include_once( __DIR__ . '/includes/wsu-embed-countdown.php' );
include_once( __DIR__ . '/includes/wsu-embed-youtube.php' );
include_once( __DIR__ . '/includes/wsu-embed-idonate.php' );
include_once( __DIR__ . '/includes/wsu-embed-google-maps.php' );
include_once( __DIR__ . '/includes/wsu-embed-tvw-video.php' );

class WSUWP_Embeds {
	public function __construct() {
		add_shortcode( 'qualtrics', array( $this, 'display_qualtrics_shortcode' ) );
		add_shortcode( 'qualtrics_multi', array( $this, 'display_qualtrics_multi_shortcode' ) );
		add_action( 'wp_head', array( $this, 'handle_qualtrics_multi_shortcode' ) );
		add_shortcode( 'cougsgive', array( $this, 'display_cougsgive' ) );
		add_shortcode( 'cougsgive_tweets', array( $this, 'display_cougsgive_tweets' ) );
		add_shortcode( 'vcea_couglink', array( $this, 'display_vcea_couglink' ) );
		add_shortcode( 'vcea_skyforge', array( $this, 'display_vcea_skyforge' ) );
	}

	public function display_vcea_couglink() {
		$output = '<div id="symp_jobswidget" data-csm="wsu-csm.symplicity.com" data-id="b2277c06aa8a3a9dc1174b690b0202c6" data-size="custom" data-css="https://wsu-csm.symplicity.com/css/list_jobs_widget.css" data-logo="" data-header-text="Engineering" data-width="320" data-height="480" data-sort-by="" ></div>
<script>(function(d, s, id) {   var js, sjs = d.getElementsByTagName(s)[0];   if (d.getElementById(id)) {return;}   js = d.createElement(s); js.id = id;   js.src = "https://static.symplicity.com/jslib/jobswidget/jobswidget.js";   sjs.parentNode.insertBefore(js, sjs); }(document, "script", "symp_jobswidget_js"));</script>';

		return $output;
	}

	/**
	 * Display a skyforge.co widget.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_vcea_skyforge( $atts ) {
		$defaults = array(
			'width' => 800,
			'height' => 500,
		);
		$atts = shortcode_atts( $defaults, $atts );

		$output = '<iframe src="https://wsu.skyforge.co/widget/" width="' . absint( $atts['width'] ) . 'px" height="' . absint( $atts['height'] ) . 'px"></iframe>';

		return $output;
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
		foreach ( $atts as $key => $url ) {
			if ( preg_match( '/https\:\/\/(.+?)\.qualtrics\.com\/(.+)/i', $url ) ) {
				$urls[] = $url;
			}
		}

		$count = count( $urls );

		$html = '<script type="application/javascript">';
		$html .= 'var sites = new Array(' . $count . ');';

		for ( $i = 0; $i < $count; $i++ ) {
			$html .= 'sites[' . $i . '] = "' . esc_url( $urls[ $i ] ) . '";';
		}

		$html .= 'var rnd = Math.floor(Math.random() * ' . $count . ');';
		$html .= 'window.location.href = sites[rnd];';
		$html .= '</script>';

		// @codingStandardsIgnoreStart
		echo $html;
		// @codingStandardsIgnoreEnd

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

	/**
	 * Display a money and donors output.
	 *
	 * @param $atts
	 *
	 * @return string
	 */
	public function display_cougsgive( $atts ) {
		$default = array(
			'money' => '0',
			'donors' => '0',
		);
		$atts = shortcode_atts( $default, $atts );

		$money = absint( str_replace( ',', '', $atts['money'] ) );
		$donors = absint( str_replace( ',', '', $atts['donors'] ) );

		$money = str_split( $money );
		$donors = str_split( $donors );

		while ( count( $money ) < 6 ) {
			array_unshift( $money, '&nbsp;&nbsp;' );
		}

		while ( count( $donors ) < 4 ) {
			array_unshift( $donors, '&nbsp;&nbsp;' );
		}

		$content = '<div class="money">';
		$cnt = 1;
		foreach ( $money as $m ) {
			if ( 4 === $cnt ) {
				$content .= '<span class="comma">,</span>';
			}

			$content .= '<span class="numberstyle">' . $m . '</span>';

			$cnt++;
		}
		$content .= '</div>';
		$content .= '<div class="donors">';
		$cnt = 1;
		foreach ( $donors as $d ) {
			if ( 2 === $cnt ) {
				$content .= '<span class="comma">,</span>';
			}

			$content .= '<span class="numberstyle">' . $d . '</span>';
			$cnt++;
		}
		$content .= '</div>';

		return $content;
	}

	public function display_cougsgive_tweets( $atts ) {
		ob_start();
		?><a class="twitter-timeline" href="https://twitter.com/hashtag/cougsgive125" data-widget-id="572888827880054785">#cougsgive125 Tweets</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
new WSUWP_Embeds();
