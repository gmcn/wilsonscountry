<?php

//address
$acf_address_line_1 = get_field( 'acf_address_line_1' );
$acf_address_line_2 = get_field( 'acf_address_line_2' );
$acf_address_line_3 = get_field( 'acf_address_line_3' );
$acf_address_line_4 = get_field( 'acf_address_line_4' );
$acf_post_code = get_field( 'acf_post_code' );

//contact numbers
$acf_main_tel_no = get_field( 'acf_main_tel_no' );
$acf_mobile_no = get_field( 'acf_mobile_no' );
$acf_fax_no = get_field( 'acf_fax_no' );
$acf_pr_email_addr = get_field( 'acf_pr_email_addr' );

//social links
$acf_facebook = get_field( 'acf_facebook' );
$acf_twitter = get_field( 'acf_twitter' );
$acf_pinterest = get_field( 'acf_pinterest' );
$acf_linked_in = get_field( 'acf_linked_in' );

 ?>


<div class="container-fluid contact-columns">
    <div class="row">
      <div class="col-md-3 social-wrapper contact-detailsheight wow fadeInLeft">
        <div class="social">
          <div class="vert-align">
            <div class="row">
              <div class="col-xs-6">
                <div class="vert-align">
                <a href="<?php echo $acf_facebook ?>" target="_blank">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/facebook_icon.svg" />
                </a>
              </div>
              </div>
              <div class="col-xs-6">
                <div class="vert-align">
                Lets get social<br  />
                <a href="<?php echo $acf_facebook ?>" target="_blank">Facebook</a>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-6">
                <div class="vert-align">
                <a href="<?php echo $acf_twitter ?>" target="_blank">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/twitter_icon.svg" />
                </a>
              </div>
              </div>
              <div class="col-xs-6">
                <div class="vert-align">
                  <a href="<?php echo $acf_twitter ?>" target="_blank">
                    Twitter
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /.col-md-3 social contact-detailsheight wow fadeInLeft -->


      <div class="col-md-4 contact contact-detailsheight wow fadeInDown">
        <div class="row">
          <div class="vert-align">
            <div class="col-xs-3">
              <div class="vert-align">
                <img src="<?php echo get_template_directory_uri(); ?>/images/email-icon.svg" />
              </div>
            </div>
            <div class="col-xs-9">
              <div class="vert-align">
                Drop us an email...<br  />
                <a href="mailto:<?php echo $acf_pr_email_addr ?>"><?php echo $acf_pr_email_addr ?></a>
              </div>
            </div>
          </div>
        </div><!-- /.row -->
        <div class="row">
          <div class="vert-align">
            <div class="col-xs-3">
              <div class="vert-align">
                <img src="<?php echo get_template_directory_uri(); ?>/images/call-icon.svg" />
              </div>
            </div>
            <div class="col-xs-9">
              <div class="vert-align">
                Call us for a chat...<br  />
                <a href="tel:<?php echo $acf_main_tel_no ?>"><?php echo $acf_main_tel_no ?></a>
              </div>
            </div>
          </div>
        </div><!-- /.row -->
      </div><!-- /.col-md-4 contact contact-detailsheight wow fadeInDown -->


      <div class="col-md-4 address-wrapper contact-detailsheight wow fadeInup">
        <div class="address">
          <div class="vert-align">
            <div class="row">
              <div class="col-xs-3">
                <div class="vert-align">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/address-icon.svg" />
                </div>
              </div>
              <div class="col-xs-9">
                <div class="vert-align">
                  Come in and say hello…<br  />
                  <?php echo $acf_address_line_1 ?><br  />
                  <?php echo $acf_address_line_2 ?><br  />
                  <?php echo $acf_address_line_3 ?><br  />
                  <?php echo $acf_address_line_4 ?><br  />
                  <?php echo $acf_post_code ?>
                </div>
              </div>
            </div><!-- /.row -->
            <div class="row">
              <div class="col-xs-3">
                <div class="vert-align">
                  <img src="<?php echo get_template_directory_uri(); ?>/images/fax-icon.svg" />
                </div>
              </div>
              <div class="col-xs-9">
                <div class="vert-align">
                  Fax us something…<br  />
                  <a href="tel:<?php echo $acf_fax_no ?>">
                    <?php echo $acf_fax_no ?>
                  </a>
                </div>
              </div>
            </div><!-- /.row -->
          </div><!-- /.vert-align -->
        </div><!-- /.address -->
      </div><!-- /.col-md-4 contact-detailsheight wow fadeInup-->


      <div class="col-md-1 hidden-xs hidden-sm contact-detailsheight red">
        <!-- empty column -->
      </div>
    </div><!-- /.row -->
  </div>
