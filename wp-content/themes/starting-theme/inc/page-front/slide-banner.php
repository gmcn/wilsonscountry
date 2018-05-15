<?php
/**
 * Note: The design shows a section in the banner 'tile' that seems to indicate that there should
 * be reference made to a particular project however none of the slides in the banner relate to
 * projects so this is not being coded.
 */
?>

<!-- BX Slider with Caption & Read More Link -->
<div id="siteslides">

	<?php if(have_rows('main_slider')): ?>

		<ul class="slider_main">

			<?php while(have_rows('main_slider')) : the_row();

					// ACF Sub fields
					$background_img = get_sub_field( 'background_img' );
					$slide_tagline1 = get_sub_field( 'slide_tagline_1' );
					$slide_tagline2 = get_sub_field( 'slide_tagline_2' );
					$slide_link = get_sub_field( 'slide_link' );
					$slide_colour = get_sub_field( 'slide_colour' );
					?>

				<li class="slide" style="background:url(<?php echo $background_img; ?>) center; background-size: cover">

            <div class="container-fluid">

              <div class="col-sm-4 col-sm-offset-8 slide-wrapper">

                <h1 style="background: <?php echo $slide_colour ?> ;" class="bxslider__title wow fadeInLeft"><?php echo $slide_tagline1; ?></h1>

								<h1 style="background: <?php echo $slide_colour ?> ;" class="bxslider__title wow fadeInLeft"><?php echo $slide_tagline2; ?></h1>

								<h2 class="wow fadeInRight clearfix" style="color: <?php echo $slide_colour ?>;">This is Wilson's Country</h2>

								<?php if( $slide_link ): ?>
									<a class="findout wow fadeInUp" href="<?php echo $slide_link ?>">{Find out more}</a>
								<?php endif; ?>

              </div>

            </div><!-- /.container -->

				</li>

			<?php endwhile; ?>

		</ul>
<?php endif; ?>

</div>
