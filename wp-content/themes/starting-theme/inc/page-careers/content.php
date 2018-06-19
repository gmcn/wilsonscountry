<?php


$careerclosing = get_field('closing_date', false, false);
$careerlocation = get_field('location');
$careersalary = get_field('salary');
$careertype = get_field('type');
$application_form = get_field('application_form');

// make date object
$careerclosing = new DateTime($date);


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
          To apply for this job click the link below to download an application form. Complete and upload via the form below before the closing date.
        </p>

        <p>
          Alternatively send your application to <a href="mailto:careers@wilsonscountry.com?Subject=<?php the_title(); ?>">careers@wilsonscountry.com</a>
        </p>

        <?php if ($application_form) : ?>

          <a href="#" class="download">Download Form</a>

        <?php endif; ?>

        <h2>Upload</h2>

        <p class="wpcf7">
          <label> Role <br>
            <span class="wpcf7-form-control-wrap your-name">
              <input type="text" name="your-name" value="" size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false" placeholder="<?php echo the_title() ?>" disabled>
            </span>
          </label>
        </p>

        <?php echo do_shortcode('[contact-form-7 id="328" title="Upload Applications"]'); ?>

      </div>


    </div>

  </div>

</div>
