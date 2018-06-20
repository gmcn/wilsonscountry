<?php if( have_rows('edu_sections') ): ?>

  <div class="container edu_sections">
    <div class="row">

    	<?php
      $i = 1;
      while( have_rows('edu_sections') ): the_row();

    		// vars
    		$section_title = get_sub_field('section_title');

    		?>

    		<div class="col-md-3 col-xs-6 col-xxs-12 edu_download">

          <div class="wrapper">

            <span><?php echo $i; ?>.</span>

            <h2><?php echo $section_title ?></h2>

            <button type="button" data-toggle="collapse" data-target="#<?php echo $i; ?>" aria-expanded="false" aria-controls="collapseExample">
              <img src="<?php echo get_template_directory_uri(); ?>/images/edu-download.png" />
            </button>

          </div>

    		</div>

    	<?php $i++; endwhile; ?>


  </div>
</div>

<?php endif; ?>
