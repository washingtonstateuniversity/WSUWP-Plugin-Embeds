<?php

class WSUWP_Embed_TVW_Video {
	/**
	 * WSUWP_Embed_TVW_Video constructor.
	 */
	public function __construct() {
		add_shortcode( 'tvw_video', array( $this, 'display_tvw_video' ) );
	}

	/**
	 * @param array $atts List of attributes passed to the shortcode.
	 *
	 * @return string Content to display for the shortcode.
	 */
	public function display_tvw_video( $atts ) {
		$defaults = array(
			'event_id' => '',
			'client_id' => '9375922947',
			'start' => 0,
			'stop' => 0,
		);
		$atts = shortcode_atts( $defaults, $atts );

		ob_start();
		?>
		<div class="invintus-player" data-eventid="<?php echo esc_attr( $atts['event_id'] ); ?>"></div>
		<script>!(function(src,cb){var s=document.createElement('script');s.src=src;s.async=true;if(s.readyState){s.onreadystatechange=function(){if(s.readyState=='loaded'||s.readyState=='complete'){s.onreadystatechange=null;cb();}};}else{s.onload=function(){cb();};}document.getElementsByTagName('head')[0].appendChild(s);})('//mediaplayer.invintusmedia.com/app.js',function(){Invintus.launch(
			{
				"clientID":"<?php echo esc_js( $atts['client_id'] ); ?>",
				"eventID":"<?php echo esc_js( $atts['event_id'] ); ?>",
				"autoStart":false,
				"simple":true
				<?php
				if ( 0 < absint( $atts['start'] ) ) {
					echo ',"startStreamAt":' . absint( $atts['start'] );
				}

				if ( 0 < absint( $atts['stop'] ) ) {
					echo ',"stopStreamAt":' . absint( $atts['stop'] );
				}
				?>
			});});</script>
		<?php
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}
}
new WSUWP_Embed_TVW_Video();
