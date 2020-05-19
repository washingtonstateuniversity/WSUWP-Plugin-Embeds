<?php

/**
 * @var $ids array IDs for the images to be displayed in the carousel
 * @var $name string Returns the name of the current instance from the shortcode params or the current page id (limits usage to one per page)
 * @var $image_size string Image size identifier, ‘thumb’, ‘thumbnail’, ‘medium’, ‘large’, ‘post-thumbnail’, or any custom image sizes
 */
?>

<!-- Swiper -->
<style>
	#swiper.swiper-container {
		width: 100%;
		height: 500px;
		margin-left: auto;
		margin-right: auto;
	}

	#swiper .swiper-slide {
		text-align: center;
		font-size: 18px;
		background: #fff;
		height: calc(100% / 2);

		/* Center slide text vertically */
		display: -webkit-box;
		display: -ms-flexbox;
		display: -webkit-flex;
		display: flex;
		-webkit-box-pack: center;
		-ms-flex-pack: center;
		-webkit-justify-content: center;
		justify-content: center;
		-webkit-box-align: center;
		-ms-flex-align: center;
		-webkit-align-items: center;
		align-items: center;

		background-size: cover;
		background-position: 50% 50%;
	}

	/* Navigation */
	.swiper-container .swiper-button-next,
	.swiper-container .swiper-button-prev {
		background: hsl(0, 0%, 100%);
		color: hsl(0, 0%, 30%);
		padding: 1em .5em 1em .8em;
		box-shadow: 0 0 25px rgba(0, 0, 0, 0.5);
		transition: 300ms ease-in-out color;
	}

	.swiper-container .swiper-button-next:after,
	.swiper-container .swiper-button-prev:after {
		font-size: 1.8em;
	}

	.swiper-container .swiper-button-next:hover,
	.swiper-container .swiper-button-prev:hover,
	.swiper-container .swiper-button-next:active,
	.swiper-container .swiper-button-prev:active {
		color: #ca1237;
	}

	.swiper-container .swiper-button-next {
		right: 0;
		border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
		padding: 1em .5em 1em .8em;
	}

	.swiper-container .swiper-button-prev {
		left: 0;
		border-top-right-radius: 5px;
		border-bottom-right-radius: 5px;
		padding: 1em .8em 1em .5em;
	}

	/* Pagination */
	.swiper-container .swiper-pagination-bullet-active {
		background: #ca1237;
	}
</style>

<div id="swiper" class="swiper-container swiper_<?php echo esc_attr($name); ?>">
	<div class="swiper-wrapper">
		<?php foreach ($ids as $photo_id) : ?>
			<?php
			$image_url = wp_get_attachment_image_src($photo_id, $image_size)[0];
			?>
			<div class="swiper-slide" style="background-image:url('<?php echo esc_attr($image_url);?>')"></div>
		<?php endforeach; ?>
	</div>

	<!-- Add Arrows -->
	<div class="swiper-button-next"></div>
	<div class="swiper-button-prev"></div>

	<!-- Add Pagination -->
	<div class="swiper-pagination"></div>
</div>
<!-- Swiper End -->

<script>
document.addEventListener("DOMContentLoaded", function() {
	const swiper = new Swiper('.swiper_<?php echo esc_js($name); ?>', {
		slidesPerView: 3,
		slidesPerColumn: 2,
		spaceBetween: 0,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
	});
});
</script>
