<article class="wsu-c-wsuwp-feed__article  wsu-c-wsuwp-feed__display-<?php echo esc_attr( $atts['display'] ); ?>">
	<header class="wsu-c-wsuwp-feed__article__header">
		<<?php echo esc_attr( $atts['title_tag'] ); ?> class="wsu-c-wsuwp-feed__article__title" id="post-<?php echo esc_attr( get_the_ID() ); ?>">
			<a href="<?php the_permalink(); ?>">
				<?php the_title(); ?>
			</a>
		</<?php echo esc_attr( $atts['title_tag'] ); ?>>
		<div class="wsu-c-wsuwp-feed__article__meta">
			<?php if ( in_array( 'author', $atts['show_post_meta'], true ) ) : ?>
			<span class="wsu-c-wsuwp-feed__article__meta__author_wrapper">
				By <span class="wsu-c-wsuwp-feed__article__meta__author"><?php the_author(); ?></span>
			</span>
			<?php endif; ?>
			<?php if ( in_array( 'date', $atts['show_post_meta'], true ) ) : ?>
			<span class="wsu-c-wsuwp-feed__article__meta__date_wrapper">
				Published on <span class="wsu-c-wsuwp-feed__article__meta__date"><?php echo esc_html( get_the_date() ); ?></span>
			</span>
			<?php endif; ?>
		</div>
	</header>
</article>
