<?php
$args = array(
  'orderby'          => 'date',
	'order'            => 'DESC',
	'post_type'        => 'post',
  'posts_per_page'   => 5,
  'post_status'      => 'publish',
);
// the query
$the_query = new WP_Query( $args ); ?>

<?php if ( $the_query->have_posts() ) : ?>

<div class="container blog">

	<!-- pagination here -->

	<!-- the loop -->
	<?php while ( $the_query->have_posts() ) : $the_query->the_post();

  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );

   ?>



    <div class="row entry">
      <div class="col-md-6 blogmatch">
        <h2><?php the_title(); ?></h2>

        <p>
          <?php
          echo wp_trim_words( get_the_content(), 60, '...' );
          ?>
        </p>


        <?php //the_content() ?>
        <a class="more" href="<?php the_permalink(); ?>">Read More ></a>
      </div>
      <div class="col-md-6 blogmatch" style="background: url('<?php echo $thumb['0'];?>') #EDEDED center; background-size: cover;">
        <span>
          <?php the_date('d.m.y'); ?>
        </span>
      </div>
    </div>





	<?php endwhile; ?>
	<!-- end of the loop -->

	<!-- pagination here -->

	<?php wp_reset_postdata(); ?>

  </div>

<?php else : ?>
  <div class="container blog">
	   <p><?php esc_html_e( 'Sorry, no posts matched your criteria.' ); ?></p>
  </div>
<?php endif; ?>
