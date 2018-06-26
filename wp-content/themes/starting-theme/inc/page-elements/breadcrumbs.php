<?php
global $post;
$header_bg_colour = get_field('header_bg_colour');
$children = get_pages( array( 'child_of' => $post->ID ) );

 ?>

<div class="breadcrumbs">
  <div class="container">
    <div class="row no-gutter">
      <div class="col-md-6 child-pages wow fadeInLeft">

        <?php
      	$args = array(
      	'post_parent' => $post->ID,
      	'post_type' => 'page',
      	'orderby' => 'menu_order'
      	);

      	$child_query = new WP_Query( $args );

      	?>

        <?php if ( ! is_page( array( 'recipes', 'careers' ) ) && is_page() && count( $children ) > 0 || is_page() && $post->post_parent || is_single()) : ?>

          <p>
            Other pages in this section:
          </p>

        <?php endif; ?>

      	<ul>

          <?php if ( $post->post_parent == '244' ) : ?>

            <li>
              <a href="/recipes/" >
              Back to Recipes
              </a>
            </li>

          <?php elseif ( $post->post_parent == '284' ) : ?>

              <li>
                <a href="/careers/" >
                Back to Careers
                </a>
              </li>

          <?php elseif ( is_page('244') || is_page('284') ) : ?>

          <?php elseif ( is_page() && count( $children ) > 0 ) : ?>

            <?php while ( $child_query->have_posts() ) : $child_query->the_post(); ?>

          	<li class="child on parent">
          		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          	</li>

          	<?php endwhile; wp_reset_postdata(); ?>

          <?php elseif ( $post->post_parent ) : ?>
            <!-- back to parent -->
           <li>
             <a href="<?php echo get_permalink( $post->post_parent ); ?>" >
             <?php echo get_the_title( $post->post_parent ); ?>
             </a>
           </li>

           <?php

              $args = array(
                  'post_type'      => 'page',
                  'post_parent'    => $post->post_parent,
                  'order'          => 'ASC',
                  'post__not_in'   => array($post->ID)
               );


              $parent = new WP_Query( $args );

              if ( $parent->have_posts() ) : ?>

                  <?php while ( $parent->have_posts() ) : $parent->the_post(); ?>
                  <!-- also a child page -->
                  <li class="children-on-child"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>

                  <?php endwhile; ?>

              <?php endif; wp_reset_postdata(); ?>

         <?php elseif (is_single()) : ?>

           <li>
             <a href="/news-blog" >
             All
             </a>
           </li>
           <li>
             <a href="/<?php echo date("Y"); ?>/<?php echo date("m"); ?>/" >
             This Month
             </a>
           </li>

         <?php endif; ?>

        </ul>

      </div>
      <div class="col-md-6 wow fadeInRight">
        <?php
        if ( function_exists('yoast_breadcrumb') ) {
        yoast_breadcrumb('
        <p id="breadcrumbs">','</p>
        ');
        }
        ?>
      </div>
    </div>
  </div>
</div>
