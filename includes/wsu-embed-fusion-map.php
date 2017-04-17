<?php

class WSUWP_Embed_Fusion_Map {
	/**
	 * WSUWP_Embed_Fusion_Map constructor.
	 *
	 * @since 0.10.0
	 */
	public function __construct() {
		add_shortcode( 'wsu_fusion_map', array( $this, 'display_wsu_fusion_map' ) );
	}

	/**
	 * Display the requested Google Map with Fusion table data.
	 *
	 * @since 0.10.0
	 *
	 * @param array $atts
	 *
	 * @return string
	 */
	public function display_wsu_fusion_map( $atts ) {
		$defaults = array(
			'map_html_id' => 'map',
			'api_key' => apply_filters( 'wsuwp_embeds_google_maps_api_key', '' ),
			'callback' => 'initMap',
			'center_lat' => '47.0068',
			'center_lng' => '-120.5360',
			'zoom' => 7,
			'template_id' => '',
			'style_id' => '',
			'fusion_table_id' => '',
		);
		$atts = shortcode_atts( $defaults, $atts );

		$options = array();
		if ( is_numeric( $atts['template_id'] ) ) {
			$options['templateId'] = absint( $atts['template_id'] );
		}

		if ( is_numeric( $atts['style_id'] ) ) {
			$options['styleId'] = absint( $atts['style_id'] );
		}

		// @codingStandardsIgnoreStart
		ob_start();
		?>
		<script type="text/javascript">
			function <?php echo esc_attr( $atts['callback'] ); ?>() {
				var map = new google.maps.Map(document.getElementById( "<?php echo esc_js( $atts['map_html_id'] ); ?>" ), {
					center: {
						lat: <?php echo esc_js( $atts['center_lat'] ); ?>,
						lng: <?php echo esc_js( $atts['center_lng'] ); ?>
					},
					zoom: <?php echo absint( $atts['zoom'] ); ?>
				});

				var layer = new google.maps.FusionTablesLayer({
					query: {
						select: 'geometry',
						from: '<?php echo esc_js( $atts['fusion_table_id'] ); ?>'
					},
					<?php
					if ( ! empty( $options ) ) {
						echo 'options: ';
						echo wp_json_encode( $options );
						echo ',';
					}
					?>
					styles: [{
						polygonOptions: {
							fillColor: '#ca1237',
							fillOpacity: 0.1,
							strokeColor: '#ca1237'
						}
					}]
				});
				layer.setMap(map);
			}
		</script>
		<div class="fusion-map-wrapper">
			<div class="fusion-map" id="<?php echo esc_attr( $atts['map_html_id'] ); ?>"></div>
		</div>
		<!-- Replace the value of the key parameter with your own API key. -->
		<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( $atts['api_key'] ); ?>&callback=<?php echo esc_attr( $atts['callback'] ); ?>"></script>
		<style>
			<?php echo '#' . esc_attr( $atts['map_html_id'] ); ?> { height: 100%; min-height: 600px; }
			.fusion-map-wrapper {
				position: relative;
				padding-bottom: 75%;
				padding-top: 30px;
				height: 0;
				overflow: hidden;
			}

			.fusion-map-wrapper .fusion-map {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
			}
		</style>
		<?php
		// @codingStandardsIgnoreEnd
		$content = ob_get_contents();
		ob_end_clean();

		return $content;
	}

}
new WSUWP_Embed_Fusion_Map();
