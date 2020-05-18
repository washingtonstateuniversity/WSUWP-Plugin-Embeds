<?php

/**
 * @var $ids array IDs for the images to be displayed in the carousel
 * @var $name string Returns the name of the current instance from the shortcode params or the current page id (limits usage to one per page)
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
</style>

<div id="swiper" class="swiper-container swiper_<?php echo $name; ?>">
	<div class="swiper-wrapper">
		<?php foreach ($ids as $photo_id) : ?>

			<?php
			$image_url = wp_get_attachment_image_src($photo_id, 'large')[0];
			?>
			<div class="swiper-slide" style="background-image:url('<?php echo $image_url;?>')"></div>
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
	const swiper = new Swiper('.swiper_<?php echo $name; ?>', {
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
