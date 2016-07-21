<?php
/**
 * Embed a Facebook post via shortcode. Most code taken quickly and lovingly from
 * Fusion Engineering's Shortcake Bakery - https://github.com/fusioneng/shortcake-bakery
 *
 * Class WSU_Embed_Facebook
 */
class WSU_Embed_Facebook {
	public function __construct() {
		add_action( 'init', array( $this, 'setup_shortcode_ui' ) );
		add_shortcode( 'wsu_facebook_post', array( $this, 'display_shortcode' ) );
	}

	public function display_shortcode( $attrs ) {
		if ( empty( $attrs['url'] ) ) {
			return '';
		}

		if ( is_admin() ) {
			echo '[wsu_facebook_post url="' . esc_url( $attrs['url'] ) . '"]';
		}

		// kses converts & into &amp; and we need to undo this
		// See https://core.trac.wordpress.org/ticket/11311
		$attrs['url'] = str_replace( '&amp;', '&', $attrs['url'] );

		// Our matching URL patterns for Facebook
		$facebook_regex = array(
			'#https?://(www)?\.facebook\.com/[^/]+/posts/[\d]+#',
			'#https?://(www)?\.facebook\.com\/video\.php\?v=[\d]+#',
			'#https?:\/\/www?\.facebook\.com\/+.*?\/videos\/[\d]+\/#',
			'#https?://(www)?\.facebook\.com\/permalink\.php\?story_fbid=[\d]+&id=[\d]+#',
			'#https?:\/\/www?\.facebook\.com\/.*?\/photos\/([^/]+)/([\d])+/#',
		);

		$match = false;
		foreach ( $facebook_regex as $regex ) {
			if ( preg_match( $regex, $attrs['url'] ) ) {
				$match = true;
			}
		}

		if ( ! $match ) {
			if ( current_user_can( 'edit_posts' ) ) {
				return '<div class="shortcake-bakery-error"><p>' . sprintf( esc_html__( 'Invalid Facebook URL: %s', 'shortcake-bakery' ), esc_url( $attrs['url'] ) ) . '</p></div>';
			} else {
				return '';
			}
		}

		wp_register_script( 'facebook-api', 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0' );
		wp_enqueue_script( 'facebook-api' );

		$out = '<div id="fb-root"></div>';
		$out .= '<div class="fb-post" data-href="' . esc_url( $attrs['url'] ) . '" data-width="500px"><div class="fb-xfbml-parse-ignore"></div></div>';
		return $out;
	}

	/**
	 * Configure support for the Facebook Embed shortcode with Shortcode UI.
	 */
	public function setup_shortcode_ui() {
		if ( ! function_exists( 'shortcode_ui_register_for_shortcode' ) ) {
			return;
		}

		$args = array(
			'label'          => 'Facebook Post',
			'listItemImage'  => 'dashicons-facebook',
			'attrs'          => array(
				array(
					'label'        => 'URL',
					'attr'         => 'url',
					'type'         => 'text',
					'description'  => 'Full URL to the Facebook Post or Video',
				),
			),
		);
		shortcode_ui_register_for_shortcode( 'wsu_facebook_post', $args );
	}
}
new WSU_Embed_Facebook();
