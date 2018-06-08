


    <?php if( have_rows('history_points') ): ?>

      <div class="container-fluid history">
        <div id="years" class="row">

    	<?php while( have_rows('history_points') ): the_row();

    		// vars
    		$image = get_sub_field('image');
    		$year = get_sub_field('year');
    		$title = get_sub_field('title');
        $paragraph = get_sub_field('paragraph');

    		?>

    		<div class="item col-md-3">

          <img class="head" src="<?php echo $image ?>" alt="<?php echo $title ?>" /><br  />
          <div class="year">
            In the Year<br  />
            <span><?php echo $year ?></span>
          </div>
          <div class="title">
            <?php echo $title ?>
          </div>
          <p>
            <?php echo $paragraph ?>
          </p>
          <img src="<?php echo get_template_directory_uri(); ?>/images/item-end.jpg" alt="<?php echo $title ?>" />
    		</div>

    	<?php endwhile; ?>

        </div>
      </div>

    <?php endif; ?>
