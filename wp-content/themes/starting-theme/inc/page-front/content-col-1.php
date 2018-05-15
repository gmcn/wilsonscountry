<?php if( have_rows('column_1') ): ?>

  <?php while( have_rows('column_1') ): the_row();

    // vars
    $background_image = get_sub_field('background_image');
    $box_title = get_sub_field('box_title');
    $box_link = get_sub_field('box_link');

    ?>

  <div class="col-row" style="background: url(<?php echo $background_image; ?>) no-repeat; background-size: cover; height: 300px;">

    <?php if( $box_title ): ?>

      <div class="title_bg">
        <h4><?php echo $box_title ?></h4>
        <a href="<?php echo $box_link; ?>">{find out more}</a>
      </div>

    <?php else : ?>

      <div class="no-title">
        <!-- <h4><?php echo $box_title ?></h4> -->
        <a href="<?php echo $box_link; ?>">{find out more}</a>
      </div>

    <?php endif; ?>

  </div>

  <?php endwhile; ?>

<?php endif; ?>
