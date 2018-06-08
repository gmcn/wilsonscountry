<?php

$header_bg_colour = get_field('header_bg_colour');

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

          <p>
            Other pages in this section:
          </p>

        	<ul>

        	<?php while ( $child_query->have_posts() ) : $child_query->the_post();

        	?>

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
