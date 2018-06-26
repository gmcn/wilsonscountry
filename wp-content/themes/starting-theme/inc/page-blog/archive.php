<div class="container-fluid intro">
	<?php if ($introbg) : ?>
		<div class="container imgbg" style="background: url(<?php echo $introbg ?>) center top no-repeat; background-size: cover;">
		</div>
	<?php endif; ?>
	<div class="container">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<h2>', '</h2>' );
			?>
		</div>
</div>

<div class="container blog">

	<?php
	if ( have_posts() ) : ?>

		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post();

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

	<?php endif; ?>

</div>
