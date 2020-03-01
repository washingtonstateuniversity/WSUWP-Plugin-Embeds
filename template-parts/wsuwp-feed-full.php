<article class="wsu-c-wsuwp-feed__article">
	<header class="wsu-c-wsuwp-feed__article__header">
		<h2 class="wsu-c-wsuwp-feed__article__title"><?php the_title(); ?></h2>
		<div class="wsu-c-wsuwp-feed__article__meta">
			<?php if ( in_array( 'author', $atts['show_post_meta'], true ) ) : ?>
			<span class=" class="wsu-c-wsuwp-feed__article__meta__author">
				By <?php the_author(); ?>
			</span>
			<?php endif; ?>
			<?php if ( in_array( 'date', $atts['show_post_meta'], true ) ) : ?>
			<span class=" class="wsu-c-wsuwp-feed__article__meta__date">
				Published on <?php the_date(); ?>
			</span>
			<?php endif; ?>
		</div>
	</header>
	<div class="wsu-c-wsuwp-feed__article__content">
		<?php the_content(); ?>
	</div>
</article>
