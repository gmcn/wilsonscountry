<div class="container-fluid careers">
  <div class="container wrapper">

    <?php
    $args = array(
    'post_parent' => $post->ID,
    'post_type' => 'page',
    'orderby' => 'menu_order'
    );

    $child_query = new WP_Query( $args );

    ?>

    <div class="row">

    <?php if ( $child_query->have_posts() ) : while ( $child_query->have_posts() ) : $child_query->the_post();

    $careerintro = get_field('intro');

    ?>

    <div class="col-md-6 job careermatch wow fadeInUp">

      <div class="jobwrapper">

        <h3><?php the_title(); ?></h3>

        <p>
          <?php echo $careerintro; ?>
        </p>

        <a href="<?php the_permalink(); ?>">
          Read More
        </a>

      </div>

    </div>

    <?php endwhile; wp_reset_postdata(); else : ?>

      <!-- No records -->

    <?php endif; ?>

    </div><!-- /.row -->

    <div class="terms">

      <p>
        By submitting an application or C.V. you are requesting to take part in a recruitment process and we need to process your personal data to facilitate this.
      </p>

      <p>
        We take the security of your data seriously and we’re committed to being transparent about how we collect and use this data and how we meet our data protection obligations.
      </p>

      <p>
        You are under no obligation to provide data to us during the recruitment process. However, if you don’t, please accept that we may not be able to process your application properly or at all.
      </p>

      <p>
        <strong>Further information can be found in our Privacy Notice (Job Applicants) in the ‘Our Policies’ section below.</strong>
      </p>

    </div>

    <div class="row novacancy">

      <div class="col-md-6">
        <p>
          If these vacancies don’t tick the box for you but you think we’re right for you, please feel free to email an enquiry to:
        </p>

        <a href="mailto:jobs@wilsonscountry.com">jobs@wilsonscountry.com</a>
      </div>

      <div class="col-md-6">
        <p>or send us your CV along with a cover letter telling us about your skills and experience and what you’re looking for.</p>

        <p>Please note that we are an Equal Opportunity Employer.</p>
      </div>

    </div>


  </div><!-- /.container -->
</div><!-- /.container-fluid -->
