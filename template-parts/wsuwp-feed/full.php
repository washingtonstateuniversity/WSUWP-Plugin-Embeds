<article class="wsu-c-wsuwp-feed__article wsu-c-wsuwp-feed__display-<?php echo esc_attr( $atts['display'] ); ?>">
	<header class="wsu-c-wsuwp-feed__article__header">
		<<?php echo esc_attr( $atts['title_tag'] ); ?> class="wsu-c-wsuwp-feed__article__title" id="post-<?php echo esc_attr( get_the_ID() ); ?>"><?php the_title(); ?></<?php echo esc_attr( $atts['title_tag'] ); ?>>
		<div class="wsu-c-wsuwp-feed__article__meta">
			<?php if ( in_array( 'author', $atts['show_post_meta'], true ) ) : ?>
			<span class=" class="wsu-c-wsuwp-feed__article__meta__author">
				By <?php the_author(); ?>
			</span>
			<?php endif; ?>
			<?php if ( in_array( 'date', $atts['show_post_meta'], true ) ) : ?>
			<span class=" class="wsu-c-wsuwp-feed__article__meta__date">
				Published on <?php echo esc_html( get_the_date() ); ?>
			</span>
			<?php endif; ?>
		</div>
	</header>
	<div class="wsu-c-wsuwp-feed__article__content">
		<?php the_content(); ?>
	</div>
</article>
