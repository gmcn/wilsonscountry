<?php if( have_rows('content_tiles') ): ?>

  <div class="container about-content">

	<?php while( have_rows('content_tiles') ): the_row();

		// vars
		$copy = get_sub_field('copy');
		$image = get_sub_field('image');

		?>

      <div class="row">

        <div class="col-md-6 green wow fadeInLeft matchheight">
          <?php echo $copy ?>
        </div>

        <div class="col-md-6 img wow fadeInRight matchheight">
          <img src="<?php echo $image ?>" alt="<?php the_title() ?>" />
        </div>

      </div>

	<?php endwhile; ?>

  </div>

<?php endif; ?>
