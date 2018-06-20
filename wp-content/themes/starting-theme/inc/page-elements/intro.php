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

     <?php if (is_single()) : ?>
       <h1>News/Blog</h1>
     <?php else : ?>
       <h1><?php the_title(); ?></h1>
     <?php endif; ?>

     <?php if( $intro ): ?>
     <h2>
       <?php echo $intro ?>
     </h2>
   <?php endif; ?>

   <?php if (is_single()) : ?>

   <?php else : ?>

       <?php the_content(); ?>

     <?php endif; ?>

   </div>
   <?php if (is_page(364)) : ?>

       <?php include(locate_template("inc/page-educational-zone/content.php")); ?>

   <?php endif; ?>
 </div>

 <?php if (is_page(364)) : ?>

   <?php include(locate_template("inc/page-educational-zone/add-content.php")); ?>

 <?php endif; ?>
