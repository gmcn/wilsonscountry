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

        <?php

        if ( is_page() && $post->post_parent || is_page() && count( $children ) > 0) : ?>
          <p>
            Other pages in this section:
          </p>

        <?php endif; ?>

      	<ul>

          <?php
            if ( $post->post_parent ) : ?>

           <li>
             <a href="<?php echo get_permalink( $post->post_parent ); ?>" >
             <?php echo get_the_title( $post->post_parent ); ?>
             </a>
           </li>

         <?php endif; ?>

         <?php

         if ( is_page() && $post->post_parent ) : ?>

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

                <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>

                <?php endwhile; ?>

            <?php endif; wp_reset_postdata(); ?>

         <?php else : ?>



         <?php endif; ?>


        	<?php while ( $child_query->have_posts() ) : $child_query->the_post(); ?>

        	<li>
        		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        	</li>

        	<?php endwhile; wp_reset_postdata(); ?>
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
