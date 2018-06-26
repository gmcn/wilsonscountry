<?php if( have_rows('content_tiles') ): ?>

  <div class="container about-content">

	<?php while( have_rows('content_tiles') ): the_row();

		// vars
    $sub_section = get_sub_field('sub_section');
    $section_header = get_sub_field('section_header');
    $section_sub_header = get_sub_field('section_sub_header');
		$copy = get_sub_field('copy');
		$image = get_sub_field('image');

		?>

      <div class="row">

        <?php if ($sub_section == 'Yes') : ?>

          <div class="container intro">

            <div class="row">
              <h1><?php echo $section_header ?></h1>
              <h2><?php echo $section_sub_header ?></h2>
            </div>


          </div>

        <?php endif; ?>

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
