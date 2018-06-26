<div class="container-fluid recipes">
  <div class="container">

    <?php
    $args = array(
    'post_parent' => $post->ID,
    'post_type' => 'page',
    'orderby' => 'menu_order'
    );

    $child_query = new WP_Query( $args );

    ?>

    <div class="row">

    <?php
    $i = 1;

    while ( $child_query->have_posts() ) : $child_query->the_post();

    $thumb = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

    ?>

    <div class="col-sm-4 recipe wow fadeInUp">

      <div class="wrapper" style="background: url('<?php echo $thumb ?>'); background-size: cover;">

        <div class="title">
          <h3> #<?php echo $i; ?> <?php the_title(); ?></h3>

          <a href="<?php the_permalink(); ?>">
            {Find out more}
          </a>
        </div>

      </div>

    </div>

  <?php $i++; endwhile; wp_reset_postdata(); ?>

    </div><!-- /.row -->


  </div><!-- /.container -->
</div><!-- /.container-fluid -->
