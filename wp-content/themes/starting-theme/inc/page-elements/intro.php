<?php

$intro = get_field('intro_paragraph');
$introbg = get_field('page_header_image');

 ?>

 <div class="container-fluid intro">
   <?php if ($introbg) : ?>
     <div class="container imgbg" style="background: url(<?php echo $introbg ?>) center top no-repeat; background-size: cover;">
     </div>
   <?php endif; ?>
   <div class="container">

     <h1><?php the_title(); ?></h1>
     
     <?php if( $intro ): ?>
     <h2>
       <?php echo $intro ?>
     </h2>
   <?php endif; ?>

       <?php the_content(); ?>

   </div>
 </div>
