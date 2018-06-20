<?php if( have_rows('edu_sections') ): ?>

  <div class="container additionalinfo">
    <div class="row">

      <?php
      $i = 1;
      while( have_rows('edu_sections') ): the_row();

        // vars
        $section_title = get_sub_field('section_title');

        ?>

        <div class="collapse" id="<?php echo $i; ?>" style="clear:both;">
          <div class="well">
            <!-- <h2><?php echo $section_title ?></h2> -->

            <div class="row">
              <?php

              // check for rows (sub repeater)
              if( have_rows('download') ): ?>
                <?php

                // loop through rows (sub repeater)
                while( have_rows('download') ): the_row();

                  // display each item as a list - with a class of completed ( if completed )
                  ?>
                  <div class="col-md-3 col-sm-6">
                    <div class="wrapper" style="background: url('<?php echo the_sub_field('download_preview'); ?>'); height: 400px; background-size: cover;">
                      <div class="title">
                        <h3><?php the_sub_field('download_title'); ?></h3>

                        <a href="<?php the_sub_field('download_file'); ?>" download>
                          {Download}
                        </a>
                      </div>
                    </div>
                  </div>
                <?php endwhile; ?>
              <?php endif; //if( get_sub_field('items') ): ?>
            </div>

          </div>
        </div>


      <?php $i++; endwhile; ?>


  </div>
</div>

<?php endif; ?>
