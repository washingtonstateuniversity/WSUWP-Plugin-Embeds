<?php

class WSUWP_Embed_Search_Form {
	public function __construct() {
		add_shortcode( 'wsuwp_search_form', array( $this, 'display_wsuwp_search_form' ) );
	}

	public function display_wsuwp_search_form( $atts ) {
		ob_start();

		?>
		<form id="searchform" class="searchform" action="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>" method="get">
			<div>
				<label class="screen-reader-text" for="s">Search for:</label>
				<input type="text" value="" name="s" id="s" />
				<input type="submit" id="searchsubmit" value="Search" />
			</div>
		</form>
		<?php

		$content = ob_get_clean();

		return $content;
	}
}
new WSUWP_Embed_Search_Form();
