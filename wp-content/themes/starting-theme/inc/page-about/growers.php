  <?php if( have_rows('growers_tiles') ): ?>

    <div class="container growers">

    <?php $i = 1; ?>

    <?php while( have_rows('growers_tiles') ): the_row();

  		// vars
  		$copy = get_sub_field('copy');
  		$image = get_sub_field('image');
      $title = get_the_title();
      $growers_name = get_sub_field('growers_name');

  		?>

        <div class="row no-gutter">



          <div class="col-md-6 wow fadeInLeft matchheight">

            <?php if($i % 2) : ?>

              <div class="vert-align">

                <?php echo $copy ?>

                <p class="name"> <?php echo $growers_name ?>  </p>

              </div>

             <?php else : ?>

               <img src="<?php echo $image ?>" alt="<?php echo $title ?>" />

             <?php endif ?>

          </div>

          <div class="col-md-6 wow fadeInRight matchheight">

            <?php if($i % 2) : ?>

              <img src="<?php echo $image ?>" alt="<?php echo $title ?>" />

             <?php else : ?>

               <div class="vert-align">

                 <?php echo $copy ?>

                 <p class="name"> <?php echo $growers_name ?>  </p>

               </div>

             <?php endif ?>

          </div>

        </div>

  	<?php $i++; endwhile; ?>

    </div>

  <?php endif; ?>
