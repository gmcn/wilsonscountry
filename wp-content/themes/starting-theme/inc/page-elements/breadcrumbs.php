<?php

$header_bg_colour = get_field('header_bg_colour');

 ?>

<div class="breadcrumbs">
  <div class="container">
    <?php
    if ( function_exists('yoast_breadcrumb') ) {
    yoast_breadcrumb('
    <p id="breadcrumbs">','</p>
    ');
    }
    ?>
  </div>
</div>
