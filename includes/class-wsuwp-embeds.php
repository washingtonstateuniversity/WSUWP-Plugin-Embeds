<?php

class WSUWP_Embeds {
	/**
	 * @since 0.9.0
	 *
	 * @var WSUWP_Embeds
	 */
	private static $instance;
	private static $version = '1.0.0';

	/**
	 * Maintains and returns the one instance. Initiate hooks when
	 * called the first time.
	 *
	 * @since 0.9.0
	 *
	 * @return \WSUWP_Embeds
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new WSUWP_Embeds();
			self::$instance->setup_hooks();
		}

		return self::$instance;
	}


	public static function get_version() {
		return self::$version;
	}

	public static function get_plugin_url() {
		return plugin_dir_url( dirname( __FILE__ ) );
	}

	public static function get_plugin_path() {
		return plugin_dir_path( dirname( __FILE__ ) );
	}

	/**
	 * Setup hooks to include.
	 *
	 * @since 0.9.0
	 */
	public function setup_hooks() {
		add_action( 'init', array( $this, 'setup_embeds' ), 2 );
		add_shortcode( 'qualtrics', array( $this, 'display_qualtrics_shortcode' ) );
		add_shortcode( 'qualtrics_multi', array( $this, 'display_qualtrics_multi_shortcode' ) );
		add_action( 'wp_head', array( $this, 'handle_qualtrics_multi_shortcode' ) );
		add_shortcode( 'cougsgive', array( $this, 'display_cougsgive' ) );
		add_shortcode( 'cougsgive_tweets', array( $this, 'display_cougsgive_tweets' ) );
		add_shortcode( 'vcea_couglink', array( $this, 'display_vcea_couglink' ) );
		add_shortcode( 'vcea_skyforge', array( $this, 'display_vcea_skyforge' ) );
		add_shortcode( 'formtool', array( $this, 'display_formtool_shortcode' ), 10, 3 );
		// Embed code for slate forms requested by Admissions
		add_shortcode( 'slateform', array( $this, 'display_slateform_shortcode' ), 10, 3 );
		// Embed code for FATV requested by SFS
		add_shortcode( 'fatv', array( $this, 'display_fatv_shortcode' ), 10, 3 );
	}


	/**
	 * Adds a slateform shortcode for emeding slate forms
	 *
	 * @since 0.13.0
	 *
	 * @param array $atts Array of shortcode attributes
	 * @param string|null $content Shortcode content
	 * @param string $tag Shortcode tag
	 *
	 */
	public function display_slateform_shortcode( $atts, $content, $tag ) {

		$html = '';

		$default_atts = array(
			'id'    => '',
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( ! empty( $atts['id'] ) ) {

			$form_id = $atts['id'];

			ob_start();

			include dirname( dirname( __FILE__ ) ) . '/displays/slateform.php';

			$html = ob_get_clean();

		} // End if

		return $html;

	} // End display_formtool_shortcode


	/**
	 * Adds a fatv shortcode for emeding videos
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Array of shortcode attributes
	 * @param string|null $content Shortcode content
	 * @param string $tag Shortcode tag
	 *
	 */
	public function display_fatv_shortcode( $atts, $content, $tag ) {

		$html = '';

		$default_atts = array(
			'id'   => '',
			'type' => 'playlist',
			'key'  => '',
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( ! empty( $atts['key'] ) && ! empty( $atts['id'] ) ) {

			$playlist_id = $atts['id'];

			$playlist_key = $atts['key'];

			$type = ( 'playlist' === $atts['type'] ) ? 'script-playlist-ng' : 'script';

			ob_start();

			include self::get_plugin_path() . '/displays/fatv-embed.php';

			$html = ob_get_clean();

		} // End if

		return $html;

	} // End display_fatv_shortcode


	/**
	 * Adds a formtool shortcode for emeding formtool forms
	 *
	 * @since 0.12.3
	 *
	 * @param array $atts Array of shortcode attributes
	 * @param string|null $content Shortcode content
	 * @param string $tag Shortcode tag
	 *
	 */
	public function display_formtool_shortcode( $atts, $content, $tag ) {

		$html = '';

		$default_atts = array(
			'url'    => '',
			'bypass' => false,
		);

		$atts = shortcode_atts( $default_atts, $atts );

		if ( ! empty( $atts['url'] ) ) {

			$url_data = explode( '?', $atts['url'] );

			if ( ! empty( $url_data[1] ) ) {

				$base_url = ( strpos( $atts['url'], 'https://secure.wsu.edu' ) === false ) ? 'https://formtool.wsu.edu/' : 'https://secure.wsu.edu/';

				$group = str_replace( $base_url, '', $url_data[0] );

				$group = explode( '/', $group );

				$group = $group[0];

				$default_params = array( 'formid' => false );

				$parse_string = wp_parse_args( $url_data[1], $default_params );

				if ( ! empty( $parse_string['formid'] ) ) {

					$form_url = $atts['url'];

					$form_id = $parse_string['formid'];

					$form_group = $group;

					$domain = $base_url;

					ob_start();

					include dirname( dirname( __FILE__ ) ) . '/displays/formtool.php';

					$html = ob_get_clean();

				} // End if
			} // End if
		} // End if

		return $html;

	} // End display_formtool_shortcode


	/**
	 * Sets up the embeds provided by this plugin and provides filters to determine
	 * which embeds should load on a given site.
	 *
	 * @since 0.9.0
	 */
	public function setup_embeds() {
		if ( apply_filters( 'wsuwp_embeds_enable_twitter', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-twitter.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_uchat', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-uchat.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_facebook_post', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-facebook-post.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_codepen', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-codepen.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_countdown', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-countdown.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_youtube', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-youtube.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_idonate', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-idonate.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_google_maps', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-google-maps.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_tvw_video', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-tvw-video.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_fusion_map', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-fusion-map.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_alphabetic_index', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-alphabetic-index.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_search_form', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-search-form.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_iframes', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-iframes.php' );
		}

		if ( apply_filters( 'wsuwp_embeds_enable_power_bi', true ) ) {
			require_once( dirname( __FILE__ ) . '/wsu-embed-powerbi.php' );
		}
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
