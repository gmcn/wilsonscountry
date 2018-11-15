

<div class="container blog">

	<!-- pagination here -->

	<!-- the loop -->
	<?php

  $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full' );

  $featuredimg = get_the_post_thumbnail();

   ?>

    <div class="row entry">
      <div class="col-md-6">
        <h2><?php the_title(); ?></h2>
        <?php the_content() ?>
        <a class="back" href="/blog">Back to Blog ></a>
      </div>
      <div class="col-md-6">
        <section>
            <div class="gal-container">

              <?php if ($featuredimg) : ?>
              <div class="col-sm-12 co-xs-12 gal-item featuredimg">
                <span>
                  <?php the_date('d.m.y'); ?>
                </span>
                <div class="box">
                  <a href="#" data-toggle="modal" data-target="#1">
                    <?php echo $featuredimg ?>
                  </a>
                  <div class="modal fade" id="1" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                      <div class="modal-content">
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <div class="modal-body">
                          <?php echo $featuredimg ?>
                        </div>
                          <div class="col-md-12 description">
                            <h4><?php echo the_title() ?></h4>
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            <?php endif ?>

              <?php if( have_rows('gallery') ): ?>

              	<?php $i=2; while( have_rows('gallery') ): the_row();

              		// vars
              		$image_video = get_sub_field('image_video');
              		$gallery_image = get_sub_field('gallery_image');
              		$video_link = get_sub_field('video_link');
              		$video_placeholder = get_sub_field('video_placeholder');
              		$media_title = get_sub_field('media_title');

              		?>

                  <?php if (!$featuredimg && $i == '2' && $image_video == 'Video') : ?>

                    <iframe width="100%" height="500" src="<?php echo $video_link ?>?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

                  <?php else : ?>

                    <div class="col-md-4 col-sm-6 co-xs-12 gal-item">
                      <div class="box">
                        <a href="#" data-toggle="modal" data-target="#<?php echo $i; ?>">


                          <?php if ($image_video == 'Video') : ?>

                            <img src="<?php echo $video_placeholder; ?>" alt="<?php echo $media_title; ?>">

                            <img class="play" src="<?php echo get_template_directory_uri(); ?>/images/play.svg" />

                          <?php else : ?>

                            <img src="<?php echo $gallery_image; ?>" alt="<?php echo $media_title; ?>">

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

                  <?php endif; ?>

              	<?php $i++; endwhile; ?>

              <?php endif; ?>



          </section>
      </div>
    </div>

  </div>
</div>
