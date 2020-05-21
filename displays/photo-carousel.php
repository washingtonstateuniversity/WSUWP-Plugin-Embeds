<?php

/**
 * @var $ids						 array IDs for the images to be displayed in the carousel.
 * @var $name						 string Returns the name of the current instance from the shortcode params or the current page id (limits usage to one per page).
 * @var $image_size					 string Image size identifier. Default medium.
 * @var $slides_per_view			 string Number of slides per view (slides visible at the same time on slider's container).
 * @var $slides_per_column			 string Number of slides per column, for multirow layout.
 * @var $space_between				 string Distance between slides in px.
 * @var $preload_images				 string When enabled Swiper will force to load all images.
 * @var $lazy						 string Enables images lazy loading. If you use slidesPerView, then you should also enable watchSlidesVisibility and Swiper will load images in currently visible slides.
 * @var $watch_slides_visibility	 string Enable this option and slides that are in viewport will have additional visible class.
 * @var $download_image_on_click	 boolean Returns true or false, if the user whats the images to download on click.
 * @var $download_image_size		 string Image size identifier. Default full.
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
		height: 100%;
		object-fit: cover;
		overflow: hidden;

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
	}


	#swiper.swiper_<?php echo esc_html($name); ?> .swiper-slide{
		height: calc((100% - <?php echo esc_html($space_between * ($slides_per_column - 1));?>px) / <?php echo esc_html($slides_per_column);?>);
	}

	#swiper [data-download-url]:hover {
		cursor: pointer;
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

		<?php if ($lazy == 'true') : ?>
			<?php foreach ($ids as $photo_id) : ?>
				<?php $image_url = wp_get_attachment_image_src($photo_id, $image_size)[0]; ?>
				<?php $download_image_url = wp_get_attachment_image_src($photo_id, $download_image_size)[0]; ?>

				<div class="swiper-slide">
					<div class="swiper-lazy-preloader"></div>
					<img data-src="<?php echo esc_url($image_url);?>" class="swiper-lazy" <?php if ($download_image_on_click == 'true') : ?> data-download-url="<?php echo esc_url($download_image_url);?>" <?php endif; ?>>
				</div>
			<?php endforeach; ?>
		<?php else: ?>
			<?php foreach ($ids as $photo_id) : ?>
				<?php $image_url = wp_get_attachment_image_src($photo_id, $image_size)[0]; ?>
				<?php $download_image_url = wp_get_attachment_image_src($photo_id, $download_image_size)[0]; ?>

				<div class="swiper-slide">
					<img src="<?php echo esc_url($image_url);?>" <?php if ($download_image_on_click == 'true') : ?> data-download-url="<?php echo esc_url($download_image_url);?>" <?php endif; ?>>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

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

	// Force download download media
	// https://stackoverflow.com/a/49500465

	function forceDownload(blob, filename) {
		var a = document.createElement('a');
		a.download = filename;
		a.href = blob;
		// For Firefox https://stackoverflow.com/a/32226068
		document.body.appendChild(a);
		a.click();
		a.remove();
	}

	// Current blob size limit is around 500MB for browsers
	function downloadResource(url, filename) {
	if (!filename) filename = url.split('\\').pop().split('/').pop();
	fetch(url, {
		headers: new Headers({
			'Origin': location.origin
		}),
		mode: 'cors'
		})
		.then(response => response.blob())
		.then(blob => {
		let blobUrl = window.URL.createObjectURL(blob);
		forceDownload(blobUrl, filename);
		})
		.catch(e => console.error(e));
	}

	const swiperSlides = document.querySelectorAll('.swiper-slide img');

	console.log(swiperSlides);


	swiperSlides.forEach(slideImage => {
		slideImage.addEventListener('click', (e) => {
			e.preventDefault();

			downloadResource(slideImage.dataset.downloadUrl);
		});
	});

	const swiper = new Swiper('.swiper_<?php echo esc_js($name); ?>', {
		slidesPerView: <?php echo esc_js($slides_per_view); ?>,
		slidesPerColumn: <?php echo esc_js($slides_per_column); ?>,
		spaceBetween: <?php echo esc_js($space_between); ?>,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		pagination: {
			el: '.swiper-pagination',
			clickable: true,
		},
		preloadImages: <?php echo esc_js($preload_images); ?>,
		lazy: <?php echo esc_js($lazy); ?>,
		watchSlidesVisibility: <?php echo esc_js($watch_slides_visibility); ?>
	});
});
</script>


