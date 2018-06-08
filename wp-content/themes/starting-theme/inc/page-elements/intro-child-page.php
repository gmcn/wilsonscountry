<?php

$intro = get_field('intro_paragraph');
$introbg = get_field('page_header_image');

 ?>

 <div class="container-fluid intro-child-page">
   <?php if ($introbg) : ?>
     <div class="container imgbg" style="background: url(<?php echo $introbg ?>) center top no-repeat; background-size: cover;">
     </div>
   <?php endif; ?>
   <div class="container">
     <h1><?php the_title(); ?></h1>
     <p>
       <?php echo $intro ?>
     </p>
     <p>
       <?php the_content(); ?>
     </p>
   </div>
 </div>
