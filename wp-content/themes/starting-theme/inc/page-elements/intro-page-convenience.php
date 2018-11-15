<?php

$intro = get_field('intro_paragraph');
$introbg = get_field('page_header_image');

 ?>

 <div class="container-fluid intro-page-convenience">
   <?php if ($introbg) : ?>
     <div class="container imgbg">
       <img src="<?php echo $introbg ?>" style="width: 100%;" alt="">
     </div>
   <?php endif; ?>
   <div class="container">
     <!-- <h1><?php the_title(); ?></h1> -->

     <div class="row no-gutter">

       <div class="col-md-6 wow fadeInLeft">
         <?php if( $intro ): ?>
         <h2>
           <?php echo $intro ?>
         </h2>
       <?php endif; ?>
         <?php the_content(); ?>
       </div>

       <div class="col-md-6 wow fadeInRight" style="text-align: center;">
         <img src="<?php echo get_template_directory_uri(); ?>/images/you-say-potato.png"  alt="<?php echo $intro ?>" />
       </div>

     </div>


   </div>
 </div>
