<?php

$recipe_prep_time     = get_field('recipe_prep_time');
$recipe_feed_amount   = get_field('recipe_feed_amount');
$thumb                = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

 ?>

<div class="container recipe">
  <div class="row">
    <div class="col-sm-4 recipematch wow fadeInRight">
      <div class="prep-time">
        <?php echo $recipe_prep_time ?>
      </div>
      <div class="feed-amount">
        <?php echo $recipe_feed_amount ?>
      </div>

        <?php if( have_rows('recipe_ingredients') ): ?>
          <div class="ingredients">
            <p>
              Ingredients:
            </p>
          	<ul>
          	<?php while( have_rows('recipe_ingredients') ): the_row();
          		// vars
          		$ingredients = get_sub_field('ingredients');
          		?>
          		<li>
          			<?php echo $ingredients ?>
          		</li>
          	<?php endwhile; ?>
          	</ul>
          </div>
        <?php endif; ?>

    </div>
    <div class="col-sm-8 recipematch wow fadeInLeft" style="background: url('<?php echo $thumb ?>'); background-size: cover;">
    </div>
  </div>
</div>

<div class="container instructions">
  <div class="row">

      <?php if( have_rows('recipe_steps') ): ?>
          <div class="col-md-12">
            <h3>Ingredients:</h3>
          </div>
          <?php $i = 1; while( have_rows('recipe_steps') ): the_row();
            // vars
            $step = get_sub_field('step');
            ?>
            <div class="col-sm-6 ingredientsmatch">
              <h4>step <?php echo $i ?></h4>
              <?php echo $step ?>
            </div>
          <?php $i++; endwhile; ?>
      <?php endif; ?>
    </div>
  </div>
</div>
