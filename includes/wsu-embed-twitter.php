<?php

class WSUWP_Embed_Twitter {
	public function __construct() {
		add_filter( 'pre_kses', array( $this, 'twitter_timeline_embed_reversal' ) );
	}

	public function twitter_timeline_embed_reversal( $content ) {
		$regex = '#<a[^>]+?class="twitter-timeline"[^>]+?href="(.*)"[^>]+?data-widget-id="(.+?)"[^>]*?>(.+?)</a>\s*?<script>(.+?)</script>#';

		$count = preg_match_all( $regex, $content, $matches );

		if ( 1 !== $count ) {
			return $content;
		}

		if ( ! isset( $matches[1][0] ) || ! isset( $matches[2][0] ) || ! isset( $matches[3][0] ) ) {
			return $content;
		}

		$href = $matches[1][0];
		$widget_id = $matches[2][0];
		$name = $matches[3][0];

		$shortcode = '[wsu_twitter_widget href="' . $href . '" id="' . $widget_id . '" name="' . $name . '"]';

		$content = str_replace( $matches[0][0], $shortcode, $content );

		return $content;
	}
}
new WSUWP_Embed_Twitter();