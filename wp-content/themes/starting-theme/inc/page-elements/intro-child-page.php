<?php

$intro = get_field('intro_paragraph');
$introbg = get_field('page_header_image');

//get parent ID
$id = wp_get_post_parent_id( $post_ID );
global $page;

 ?>

 <div class="container-fluid intro-child-page">
   <?php if ($introbg) : ?>
     <div class="container imgbg" style="background: url(<?php echo $introbg ?>) center top no-repeat; background-size: cover;">
     </div>
   <?php endif; ?>

   <div class="container">

      <?php wp_get_post_parent_id( $post_ID ); ?>

     <?php if ( is_page() && $post->post_parent ) : ?>

       <h1><?php echo get_the_title( $post->post_parent );  ?></h1>

     <?php endif; ?>

     <?php if ( $id != '284' ) : ?>

       <h1><?php the_title(); ?></h1>

     <?php endif; ?>

     <?php if( $intro ): ?>
     <h2>
       <?php echo $intro ?>
     </h2>
   <?php endif; ?>

     <?php if ( $id == '284' ) : ?>

       <p>
         We are always on the lookout to expand our team. Employes will work closely together to ensure all areas of the business run effectively and efficiently.
       </p>

     <?php endif; ?>

     <?php if ( $id != '284' ) : ?>

       <?php the_content(); ?>

     <?php endif; ?>
   </div>
 </div>
