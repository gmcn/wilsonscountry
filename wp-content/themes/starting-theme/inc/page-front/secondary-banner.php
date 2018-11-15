<?php
/**
 * Note: The design shows a section in the banner 'tile' that seems to indicate that there should
 * be reference made to a particular project however none of the slides in the banner relate to
 * projects so this is not being coded.
 */
?>

<!-- BX Slider with Caption & Read More Link -->
<div class="second-slider">

	<?php if(have_rows('secondary_slider')): ?>

		<ul class="secondary_slider">

			<?php while(have_rows('secondary_slider')) : the_row();

					// ACF Sub fields
					$background_img = get_sub_field( 'background_img' );
					$slide_tagline1 = get_sub_field( 'slide_tagline_1' );
					$slide_tagline2 = get_sub_field( 'slide_tagline_2' );
					$slide_icon = get_sub_field( 'slide_icon' );
					?>

				<li class="slide" style="background:url(<?php echo $background_img; ?>) center; background-size: cover">

            <div class="container-fluid">

							<div class="row">

		            <div class="col-sm-8 col-sm-offset-3 slide-wrapper">

									<div class="row">

										<div class="col-sm-4 wow fadeInLeft matchheight">
											<div class="vert-align">

												<h1 class="bxslider__title "><?php echo $slide_tagline1; ?></h1>

												<h1 class="bxslider__title"><?php echo $slide_tagline2; ?></h1>

												<?php if( $slide_link ): ?>
												<a class="findout" href="<?php echo $slide_link ?>">{Find out more}</a>
												<?php endif; ?>

											</div>

										</div><!-- /.col-sm-4 -->

										<div class="col-md-1 hidden-xs wow fadeInUp matchheight">
											<img src="<?php echo get_template_directory_uri(); ?>/images/<?php echo $slide_icon ?>-icon.svg" alt="<?php echo $slide_tagline1; ?> <?php echo $slide_tagline2; ?>" />
										</div><!-- /.col-sm-1 -->

										<div class="col-md-2 hidden-sm hidden-xs wow fadeInRight matchheight">
											<img src="<?php echo get_template_directory_uri(); ?>/images/potato-icon.svg" alt="<?php echo $slide_tagline1; ?> <?php echo $slide_tagline2; ?>" />
										</div><!-- /.col-sm-2 -->

									</div>

		            </div><!-- /.col-sm-6 col-sm-offset-3 slide-wrapper -->

							</div><!-- /.row -->

            </div><!-- /.container -->

				</li>

			<?php endwhile; ?>

		</ul>

<?php endif; ?>

</div>
