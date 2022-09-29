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
		$regexes = array();

		// HTML posted through the text editor survives.
		$regexes[] = '#<a[^>]+?class="twitter-timeline"[^>]+?href="(.*)"[^>]+?data-widget-id="(.+?)"[^>]*?>(.+?)</a>\s*?<script>(.+?)</script>#';

		// The visual editor in WordPress encodes HTML tags before sending.
		$regexes[] = '#&lt;a(?:[^&]|&(?!gt;))+?class="twitter-timeline"(?:[^&]|&(?!gt;))+?href="(.*?)"(?:[^&]|&(?!gt;))+?data-widget-id="(.*?)"[\s]*&gt;(.*?)&lt;/a&gt;(?:[^&]|&(?!gt;))*?&lt;script(.*?)/script&gt;#';

		foreach ( $regexes as $regex ) {
			if ( ! $count = preg_match_all( $regex, $content, $matches ) ) {
				continue;
			}

			if ( 1 !== $count ) {
				continue;
			}

			if ( ! isset( $matches[1][0] ) || ! isset( $matches[2][0] ) || ! isset( $matches[3][0] ) ) {
				continue;
			}

			$href = $matches[1][0];
			$widget_id = $matches[2][0];
			$name = $matches[3][0];

			$shortcode = '[wsu_twitter_timeline href="' . $href . '" data_widget_id="' . $widget_id . '" name="' . $name . '"]';

			$content = str_replace( $matches[0][0], $shortcode, $content );
		}

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
			'data_widget_id' => '',
			'name' => '',
		);
		$atts = shortcode_atts( $defaults, $atts );

		ob_start();

		include WSUWP_Embeds::get_template_path() . 'twitter.php';
		
		$content = ob_get_contents();

		ob_end_clean();

		return $content;
	}
}
new WSUWP_Embed_Twitter();
