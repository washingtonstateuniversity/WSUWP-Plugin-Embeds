<div class="wsu-c-card__wrapper">
	<div class="wsu-c-card__container">
		<div class="wsu-c-card__content">
			<?php if ( ! empty( $args['img_src'] ) ) : ?><div class="wsu-c-card__photo-frame">
				<img class="wsu-c-card__photo" src="https://source.unsplash.com/collection/895539" alt="#" data-object-fit="">
			</div><?php endif; ?>
			<<?php echo esc_attr( $atts['title_tag'] ); ?> class="wsu-c-card__heading" id="post-<?php echo esc_attr( get_the_ID() ); ?>">
				<a href="<?php the_permalink(); ?>">
					<?php the_title(); ?>
				</a>
			</<?php echo esc_attr( $atts['title_tag'] ); ?>>
			<?php if ( in_array( 'excerpt', $atts['show'], true ) ) : ?>
			<p class="wsu-c-card__description">
				<?php the_excerpt(); ?>
			</p>
			<?php endif; ?>
			<?php if ( in_array( 'date', $atts['show'], true ) ) : ?>
			<div class="wsu-c-card__publish-date">
				Published on <span class="wsu-c-card__publish-date__date"><?php echo esc_html( get_the_date() ); ?></span>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>
