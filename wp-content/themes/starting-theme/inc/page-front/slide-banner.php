<?php
/**
 * Note: The design shows a section in the banner 'tile' that seems to indicate that there should
 * be reference made to a particular project however none of the slides in the banner relate to
 * projects so this is not being coded.
 */
?>

<!-- BX Slider with Caption & Read More Link -->
<div id="siteslides">

<?php if (function_exists('get_field')) : ?>
	<?php if(have_rows('banner_slides')): ?>

		<ul class="slider_main">

			<?php while(have_rows('banner_slides')) : the_row();

					// ACF Sub fields
					$slide_title = get_sub_field( 'slide_title' );
					$slide_image = get_sub_field( 'slide_image' );
					$slide_caption = get_sub_field( 'slide_caption' );
					$slide_url = get_sub_field( 'slide_url' ); ?>

				<li class="slide" style="background:url(<?php echo $slide_image; ?>) center; background-size: cover">

          <div class="shadow">
            <div class="container">

              <div class="col-sm-3 col-sm-offset-5">

                <h2 class="bxslider__title wow fadeInLeft"><?php echo $slide_title; ?></h2>

                </div>

            </div><!-- /.container -->
          </div><!-- /.shadow -->

				</li>

			<?php endwhile; ?>

		</ul>

		<div class="scrolldown">
			<img class="wow fadeInDown" src="<?php echo get_template_directory_uri(); ?>/images/down.png" alt="Scroll Down" />
		</div>

	<?php endif; ?>
<?php endif; ?>

</div>
