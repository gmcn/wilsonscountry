<div class="container-fluid form-columns">
  <div class="row">
    <div class="col-md-1 hidden-xs hidden-sm matchheight red">
      <!-- empty column -->
    </div>
    <div class="col-md-10 matchheight wow fadeInUp">
      <div class="form-wrapper">
        <div class="row">
          <div class="col-md-1 hidden-xs hidden-sm airplane contactformheight">
            <img src="<?php echo get_template_directory_uri(); ?>/images/airplane.svg" />
          </div>
          <div class="col-md-8 col-md-offset-1 contactformheight">
            <p>
              Send us a message...
            </p>
            <?php echo FrmFormsController::get_form_shortcode( array( 'id' => 2, 'title' => false, 'description' => false ) ); ?>
          </div>
        </div>

      </div>

    </div>
    <div class="col-md-1 hidden-xs hidden-sm matchheight red">
      <!-- empty column -->
    </div>
  </div>
</div>
