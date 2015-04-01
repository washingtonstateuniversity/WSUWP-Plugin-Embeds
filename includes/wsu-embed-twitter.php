<?php

class WSUWP_Embed_Twitter {
	public function __construct() {
		add_filter( 'pre_kses', array( $this, 'twitter_timeline_embed_reversal' ) );
		add_shortcode( 'wsu_twitter_widget', array( $this, 'display_wsu_twitter_widget' ) );
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

	public function display_wsu_twitter_widget( $atts ) {
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