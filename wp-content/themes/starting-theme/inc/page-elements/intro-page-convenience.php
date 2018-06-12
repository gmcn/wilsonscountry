<?php

$intro = get_field('intro_paragraph');
$introbg = get_field('page_header_image');

 ?>

 <div class="container-fluid intro-page-convenience">
   <?php if ($introbg) : ?>
     <div class="container imgbg" style="background: url(<?php echo $introbg ?>) center top no-repeat; background-size: cover;">
     </div>
   <?php endif; ?>
   <div class="container">
     <!-- <h1><?php the_title(); ?></h1> -->

     <div class="row no-gutter">

       <div class="col-md-6">
         <h2>
           <?php echo $intro ?>
         </h2>
         <?php the_content(); ?>
       </div>

       <div class="col-md-6" style="text-align: center;">
         <img src="<?php echo get_template_directory_uri(); ?>/images/you-say-potato.png"  alt="<?php echo $intro ?>" />
       </div>

     </div>


   </div>
 </div>
