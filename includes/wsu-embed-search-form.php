<?php

class WSUWP_Embed_Search_Form {
	public function __construct() {
		add_shortcode( 'wsuwp_search_form', array( $this, 'display_wsuwp_search_form' ) );
	}

	public function display_wsuwp_search_form( $atts ) {
		$defaults = array(
			'site_category_slug' => '',
			'tag_slug' => '',
		);

		$atts = shortcode_atts( $defaults, $atts );

		ob_start();

		?>
		<form id="searchform" class="searchform" action="<?php echo esc_url( trailingslashit( get_home_url() ) ); ?>" method="get">
			<div>
				<label class="screen-reader-text" for="s">Search for:</label>
				<input type="text" value="" name="s" id="s" />
				<?php
				foreach ( $atts as $att => $slug ) {

					if ( '' === $slug ) {
						continue;
					}

					if ( 'site_category_slug' === $att ) {
						$taxonomy = 'category';
						$parameter = 'category_name';
					}

					if ( 'tag_slug' === $att ) {
							$taxonomy = 'post_tag';
							$parameter = 'tag';
					}

					$term = get_term_by( 'slug', esc_html( $slug ), $taxonomy );

					if ( $term ) {
						?>
						<input type="hidden"
							   value="<?php echo esc_attr( $slug ); ?>"
							   name="<?php echo esc_attr( $parameter ); ?>" />
						<?php
					}
				}
				?>
				<input type="submit" id="searchsubmit" value="Search" />
			</div>
		</form>
		<?php

		$content = ob_get_clean();

		return $content;
	}
}
new WSUWP_Embed_Search_Form();
