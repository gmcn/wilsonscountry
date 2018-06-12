<div class="container-fluid ranges">
  <div class="container wrapper">

    <?php
    $args = array(
    'post_parent' => $post->ID,
    'post_type' => 'page',
    'orderby' => 'menu_order'
    );

    $child_query = new WP_Query( $args );

    ?>

    <div class="row">

    <?php while ( $child_query->have_posts() ) : $child_query->the_post();

    $range_heading_image = get_field('range_heading_image');
    $range_info = get_field('range_info');

    ?>

    <div class="col-sm-4 wow fadeInUp">

      <!-- <a href="<?php the_permalink(); ?>"> -->
        <img src="<?php echo $range_heading_image; ?>" alt="<?php the_title(); ?>" />
      <!-- </a> -->

      <p>
        <?php echo $range_info; ?>
      </p>

      <a href="<?php the_permalink(); ?>">
        {Find out more}
      </a>

    </div>

    <?php endwhile; wp_reset_postdata(); ?>

    </div><!-- /.row -->


  </div><!-- /.container -->
</div><!-- /.container-fluid -->
