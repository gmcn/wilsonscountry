<div class="container-fluid careers">
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

    $careerintro = get_field('intro');

    ?>

    <div class="col-md-6 job careermatch wow fadeInUp">

      <div class="jobwrapper">

        <h3><?php the_title(); ?></h3>

        <p>
          <?php echo $careerintro; ?>
        </p>

        <a href="<?php the_permalink(); ?>">
          Read More
        </a>

      </div>

    </div>

    <?php endwhile; wp_reset_postdata(); ?>

    </div><!-- /.row -->


  </div><!-- /.container -->
</div><!-- /.container-fluid -->
