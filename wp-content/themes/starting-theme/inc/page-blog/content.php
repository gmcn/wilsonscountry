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
        <?php the_content() ?>
      </div>
      <div class="col-md-6">
        <section>
            <div class="gal-container">
              <div class="col-sm-12 co-xs-12 gal-item">
                <div class="box">
                  <a href="#" data-toggle="modal" data-target="#1">
                    <?php echo the_post_thumbnail(); ?>
                  </a>
                  <div class="modal fade" id="1" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div class="modal-body">
                          <?php echo the_post_thumbnail(); ?>
                        </div>
                          <div class="col-md-12 description">
                            <h4><?php echo the_title() ?></h4>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <?php if( have_rows('gallery') ): ?>

              	<?php $i=2; while( have_rows('gallery') ): the_row();

              		// vars
              		$image_video = get_sub_field('image_video');
              		$gallery_image = get_sub_field('gallery_image');
              		$video_link = get_sub_field('video_link');
              		$video_placeholder = get_sub_field('video_placeholder');
              		$media_title = get_sub_field('media_title');

              		?>

                  <div class="col-md-4 col-sm-6 co-xs-12 gal-item blogmatch">
                    <div class="box">
                      <a href="#" data-toggle="modal" data-target="#<?php echo $i; ?>">
                        <img src="<?php echo $gallery_image; ?>" alt="<?php echo $media_title; ?>">

                        <?php if ($image_video == 'Video') : ?>

                        <img class="play" src="<?php echo get_template_directory_uri(); ?>/images/play.svg" />

                        <?php endif; ?>

                      </a>
                      <div class="modal fade" id="<?php echo $i; ?>" tabindex="-1" role="dialog">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <div class="modal-body">

                              <?php if ($image_video == 'Image') : ?>

                                <img src="<?php echo $gallery_image; ?>" alt="<?php echo $media_title; ?>">

                              <?php elseif ($image_video == 'Video') : ?>

                                <iframe width="100%" height="800" src="<?php echo $video_link ?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

                              <?php endif; ?>



                            </div>
                              <div class="col-md-12 description">
                                <h4><?php echo $media_title; ?></h4>
                              </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

              			<?php if( $link ): ?>
              				<a href="<?php echo $link; ?>">
              			<?php endif; ?>

              				<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt'] ?>" />

              			<?php if( $link ): ?>
              				</a>
              			<?php endif; ?>

              		    <?php echo $content; ?>

              	<?php $i++; endwhile; ?>

              <?php endif; ?>



          </section>
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
