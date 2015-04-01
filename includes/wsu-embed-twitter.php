<?php
/**
 * Class WSUWP_Embed_Twitter
 */
class WSUWP_Embed_Twitter {
	/**
	 * Setup hooks.
	 */
	public function __construct() {
		add_filter( 'pre_kses', array( $this, 'twitter_timeline_embed_reversal' ) );
		add_shortcode( 'wsu_twitter_timeline', array( $this, 'display_wsu_twitter_timeline' ) );
	}

	/**
	 * Parse content for Twitter Timeline embed code, likely copied from a profile or search
	 * result on twitter.com and reverse it into our `wsu_twitter_timeline` shortcode.
	 *
	 * @param string $content Content passed through pre_kses.
	 *
	 * @return string Modified content.
	 */
	public function twitter_timeline_embed_reversal( $content ) {
		$regex = '#<a[^>]+?class="twitter-timeline"[^>]+?href="(.*)"[^>]+?data-widget-id="(.+?)"[^>]*?>(.+?)</a>\s*?<script>(.+?)</script>#';

		$count = preg_match_all( $regex, $content, $matches );

		// Only look for one instance at this point.
		if ( 1 !== $count ) {
			return $content;
		}

		// A matched instance must have 3 pieces of information for us.
		if ( ! isset( $matches[1][0] ) || ! isset( $matches[2][0] ) || ! isset( $matches[3][0] ) ) {
			return $content;
		}

		$href = $matches[1][0];
		$widget_id = $matches[2][0];
		$name = $matches[3][0];

		$shortcode = '[wsu_twitter_timeline href="' . $href . '" id="' . $widget_id . '" name="' . $name . '"]';

		$content = str_replace( $matches[0][0], $shortcode, $content );

		return $content;
	}

	/**
	 * Display Twitter timeline embed code based on attributes passed with a shortcode.
	 *
	 * @param array $atts
	 *
	 * @return string Content to display for the shortcode.
	 */
	public function display_wsu_twitter_timeline( $atts ) {
		$defaults = array(
			'href' => '',
			'id' => '',
			'name' => '',
		);
		$atts = shortcode_atts( $defaults, $atts );

		ob_start();
		?><a class="twitter-timeline"
			 href="<?php echo esc_url( $atts['href'] ); ?>"
			 data-widget-id="<?php echo esc_attr( $atts['id'] ); ?>"><?php echo esc_html( $atts['name'] ); ?></a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script><?php

		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
new WSUWP_Embed_Twitter();