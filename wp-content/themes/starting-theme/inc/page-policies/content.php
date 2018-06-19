<?php if( have_rows('polices') ): ?>

  <div class="container policies">
    <div class="row">

    	<?php while( have_rows('polices') ): the_row();

    		// vars
    		$policy_title = get_sub_field('policy_title');
    		$policy_description = get_sub_field('policy_description');
    		$policy_link = get_sub_field('policy_link');
        $policy_download = get_sub_field('policy_download');

    		?>

    		<div class="col-md-6 policy">

          <h2><?php echo $policy_title ?></h2>

          <?php echo $policy_description ?>


          <div class="row">
            <div class="col-md-6">
              <?php if( $policy_link ): ?>
        				<a href="<?php echo $policy_link ?>" target="_blank"><?php echo $policy_link ?></a>
        			<?php endif; ?>
            </div>
            <div class="col-md-6">
              <?php if( $policy_download ): ?>
        				<a href="<?php echo $policy_download ?>" download><img src="<?php echo get_template_directory_uri(); ?>/images/download.svg" alt="download <?php echo $policy_title ?>" /></a>
        			<?php endif; ?>
            </div>
          </div>


  		    <?php echo $content; ?>

    		</div>

    	<?php endwhile; ?>


  </div>
</div>

<?php endif; ?>
