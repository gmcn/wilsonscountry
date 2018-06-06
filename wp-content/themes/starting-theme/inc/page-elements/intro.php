<?php

$intro = get_field('intro_paragraph');

 ?>


<div class="container-fluid intro">
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
