

  <?php if( have_rows('products') ): ?>

    <?php $i = 1; ?>

    <div class="container-fluid products">

      <div class="container">

        <div class="row">

        <?php while( have_rows('products') ): the_row();

      		// vars
      		$product_title = get_sub_field('product_title');
      		$product_colour = get_sub_field('product_colour');
          $product_img = get_sub_field('product_img');
          $product_text = get_sub_field('product_text');
          $product_recipies = get_sub_field('product_recipies');

      		?>

            <div class="col-md-6 col-xs-12 wow hary<?php if($i % 2) : ?> fadeInLeft <?php else : ?> fadeInRight <?php endif ?>matchheight">

              <div class="wrapper productmatch" style="border-top: 5px solid <?php echo $product_colour ?>">
                <div class="row">

                  <div class="col-xs-6 col-xxs-12">
                    <img src="<?php echo $product_img ?>" alt="<?php echo $product_title ?>" />
                  </div>

                  <div class="col-xs-6 col-xxs-12">
                    <h3 style="background: <?php echo $product_colour ?>"><?php echo $product_title; ?></h3>
                    <p>
                      <?php echo $product_text ?>
                    </p>

                    <?php if ($product_recipies) : ?>
                      <a href="<?php echo $product_recipies ?>">Click Here to view recipies for this product.</a>
                    <?php endif ?>

                  </div>

                </div>
              </div>








            </div>

      	<?php $i++; endwhile; ?>

        </div><!-- /.row -->
      </div><!-- /.container -->
    </div><!-- /.container-fluid products -->

  <?php endif; ?>
