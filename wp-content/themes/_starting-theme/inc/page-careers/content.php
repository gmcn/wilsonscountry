<?php


$careerclosing = get_field('closing_date', false, false);
$careerlocation = get_field('location');
$careersalary = get_field('salary');
$careertype = get_field('type');
$external_apply_link = get_field('external_apply_link');
$application_form = get_field('application_form');
$monitoring_form = get_field('monitoring_form');

// make date object
$careerclosing = new DateTime($careerclosing);


 ?>


<div class="container career">

  <div class="row">

    <h1>Available Job Listings:</h1>

    <div class="col-md-8 careermatch wow fadeInRight">
      <div class="row">
        <div class="col-md-6 closingdate">
          Closing Date: <?php echo $careerclosing->format('j.m.y'); ?>
        </div>
        <div class="col-md-6 posteddate">
          Job Posted: <?php the_date('j.m.y'); ?>
        </div>
      </div>
      <h2>The Role</h2>
      <h3><?php the_title(); ?></h3>
      <h4><?php echo $careerintro ?></h4>
      <div class="details">
        <span>Location:</span> <?php echo $careerlocation ?><br />
        <span>Salary:</span> <?php echo $careersalary ?><br />
        <span>Type:</span> <?php echo $careertype ?>
      </div>
      <?php the_content(); ?>
    </div>

    <div class="col-md-4 careermatch wow fadeInLeft">

      <div class="applywrapper">
        Begin your application now
        <h2>Apply</h2>
        <p>
          Apply for this position
        </p>

        <p>
          <?php if($external_apply_link) : ?>
            To apply for this job click the link below.
          <?php else : ?>
            To apply for this job click the link below to download an application form. Complete and upload via the form below before the closing date.
          <?php endif ?>
        </p>

        <!-- <p>
          Alternatively send your application to <a href="mailto:jobs@wilsonscountry.com?Subject=<?php the_title(); ?>">jobs@wilsonscountry.com</a>
        </p> -->

        <?php if($external_apply_link) : ?>

          <a href="<?php echo $external_apply_link ?>" class="download" target="_blank">Apply Now</a>

        <?php else : ?>

          <?php if ($application_form) : ?>

            <a href="<?php echo $application_form ?>" class="download" download>Download Application Form</a>

          <?php endif; ?>

          <?php if ($monitoring_form) : ?>

            <a href="<?php echo $monitoring_form ?>" class="download" download>Download Monitoring Form</a>

          <?php endif; ?>

        <?php endif; ?>

        <?php if(!$external_apply_link) : ?>

        <h2>Upload</h2>

        <?php echo do_shortcode('[contact-form-7 id="328" title="Upload Applications"]'); ?>

        <?php endif; ?>

      </div>


    </div>

  </div>

</div>
