<div class="container-fluid nav-columns">
  <div class="row">
    <div class="col-md-1 hidden-sm hidden-md matchheight grey">
      <!-- empty column -->
    </div>
    <div class="col-md-3 col-lg-2 matchheight wow fadeInLeft column-1">
      <?php
        include(locate_template("inc/page-front/content-col-1.php"));
       ?>
    </div>
    <div class="col-md-1 hidden-xs hidden-sm matchheight grey">
      <div class="vert-align">
        <a href="mailto:info@wilsonscountry.com">
          <img class="wow fadeInDown" src="<?php echo get_template_directory_uri(); ?>/images/contact-icon.svg"  />
        </a>
        <a href="tel:+442838391029">
          <img class="wow fadeInUp" src="<?php echo get_template_directory_uri(); ?>/images/phone-icon.svg"  />
        </a>
      </div>
    </div>
    <div class="col-md-4 col-lg-3 matchheight wow fadeInRight column-2">

      <?php
        include(locate_template("inc/page-front/content-col-2.php"));
       ?>

    </div>
    <div class="col-md-4 matchheight wow fadeInRight column-3">

      <?php
        include(locate_template("inc/page-front/content-col-3.php"));
       ?>

    </div>
    <div class="col-md-1 hidden-sm hidden-md matchheight grey">
      <!-- empty column -->
    </div>
  </div>
</div>
