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
 * @var $pagination_type			 string The type of pagination to display. Can be "bullets", "fraction", or "progressbar". Default bullets.
 */
?>

<!-- Swiper -->
<style>
	:root {
		--swiper-theme-color: #ca1237 !important;
	}

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

	#swiper.swiper-container .swiper-slide {
		width: 100%;
		height: 100%;
		object-fit: cover;
		object-position: 50% 50%;
	}

	#swiper [data-download-url]:hover {
		cursor: pointer;
	}

	/* Navigation */
	.swiper-container .swiper-button-next,
	.swiper-container .swiper-button-prev {
		background: hsl(0, 0%, 100%);
		color: hsl(0, 0%, 30%);
		transition: 300ms ease-in-out all;
		border-radius: 100%;
		width: 50px;
		height: 50px;
		border: 1px solid hsla(0, 0%, 90%)
	}

	.swiper-container .swiper-button-next:after,
	.swiper-container .swiper-button-prev:after {
		font-size: 1.8em;
	}

	.swiper-container .swiper-button-next:hover,
	.swiper-container .swiper-button-prev:hover,
	.swiper-container .swiper-button-next:active,
	.swiper-container .swiper-button-prev:active {
		color: var(--swiper-theme-color);
		box-shadow: 0 1px 8px rgba(0, 0, 0, 0.2);
	}

	.swiper-container .swiper-button-next:after,
	.swiper-container .swiper-button-prev:after {
		font-family: "wsu-icons" !important;
		font-size: 24px;
		width: 20px;
		text-align: center;
		text-indent: -2px;
		position: absolute;
		left: 50%;
		top: 50%;
		transform: translate(-50%, -50%);
	}

	.swiper-container .swiper-button-next {
		right: 1em;
	}

	.swiper-container .swiper-button-next:after {
		content: "\F107";
		margin-left: 2px;
	}

	.swiper-container .swiper-button-prev {
		left: 1em;
	}

	.swiper-container .swiper-button-prev:after {
		content: "\F105";
		margin-right: 2px;
	}

	/* Pagination Styles */
	.swiper-container.swiper-container-horizontal>.swiper-pagination-bullets,
	.swiper-container.swiper-pagination-custom,
	.swiper-container.swiper-pagination-fraction {
		width: initial;
		left: 50%;
		background: white;
		border-radius: 5px;
		padding: 0 7px 2px;
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

	// Create click event listener
	const swiperSlides = document.querySelectorAll('.swiper-slide img');

	swiperSlides.forEach(slideImage => {
		slideImage.addEventListener('click', (e) => {
			e.preventDefault();

			if (typeof slideImage.dataset.downloadUrl !== "undefined") {
				downloadResource(slideImage.dataset.downloadUrl);
			}
		});
	});

	// Init swiper
	const swiper = new Swiper('.swiper_<?php echo esc_js($name); ?>', {
		slidesPerView: 1,
		slidesPerColumn: 1,
		spaceBetween: 10,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		pagination: {
			el: '.swiper-pagination',
			type: '<?php echo esc_js($pagination_type); ?>',
			clickable: true,
		},
		preloadImages: <?php echo esc_js($preload_images); ?>,
		lazy: <?php echo esc_js($lazy); ?>,
		watchSlidesVisibility: <?php echo esc_js($watch_slides_visibility); ?>,
		keyboard: {
			enabled: true
		},
		autoplay: {
			enabled: <?php echo esc_js($autoplay); ?>,
			delay: <?php echo esc_js($autoplay_delay); ?>,
		},
		breakpoints: {
			576: {
				slidesPerView: 2,
				slidesPerColumn: 1,
				spaceBetween: 20,
			},
			// when window width is >= 1200px
			768: {
				slidesPerView: <?php echo esc_js($slides_per_view); ?>,
				slidesPerColumn: <?php echo esc_js($slides_per_column); ?>,
				spaceBetween: <?php echo esc_js($space_between); ?>,
			}
		}
		// TODO: Add breakpoints
	});



});
</script>

