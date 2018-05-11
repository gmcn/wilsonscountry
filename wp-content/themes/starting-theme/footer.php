<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Starting_Theme
 */

?>

	</div><!-- #content -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

	<footer>
		<div class="container site-info">

			<div class="row">
				<div class="col-md-2 footermatch wow fadeInUp">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img class="footer__logo" src="<?php echo get_template_directory_uri(); ?>/images/wilsonscountry-logo_white.svg" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
					<span>© Wilson’s Country 2018</span>
				</div>
				<div class="col-md-5 footermatch wow fadeInUp">
					<p>
						The farming life is a school of patience;
					</p>
					<p>
						you can’t speed up the crops,	nor make the rain fall,	but the wait is worth	what comes in the taste.
					</p>
				</div>
				<div class="col-md-2 col-md-push-3 social footermatch wow fadeInUp">
					<a href="https://www.facebook.com/WilsonsCountry/" target="_blank">
						<img src="<?php echo get_template_directory_uri(); ?>/images/facebook_icon.svg" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
					<a href="https://twitter.com/WilsonsCountry/" target="_blank">
						<img src="<?php echo get_template_directory_uri(); ?>/images/twitter_icon.svg" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
					<span class="cornell">Website by <strong><a href="https://www.cornellstudios.com">Cornell</a></strong></span>
				</div>
			</div><!-- /.row -->
		</div><!-- /.container .site-info -->
	</footer>

<?php wp_footer(); ?>

</body>

</html>
