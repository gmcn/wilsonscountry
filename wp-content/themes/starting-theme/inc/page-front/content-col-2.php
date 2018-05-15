
<?php if( have_rows('column_2') ): ?>

 <?php while( have_rows('column_2') ): the_row();

   // vars
   $background_image = get_sub_field('background_image');
   $title_bg_colour = get_sub_field('title_bg_colour');
   $box_title = get_sub_field('box_title');
   $box_link = get_sub_field('box_link');

   ?>

 <div class="col-row" style="background: url(<?php echo $background_image; ?>) no-repeat; background-size: cover; height: 300px;">

   <div class="title_bg" style="background: <?php echo $title_bg_colour ?>">
     <h4><?php echo $box_title ?></h4>
     <a href="<?php echo $box_link; ?>">{find out more}</a>
   </div>

 </div>

 <?php endwhile; ?>

<?php endif; ?>
