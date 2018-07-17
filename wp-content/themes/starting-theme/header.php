<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Starting_Theme
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link type="text/plain" rel="robots" href="<?php echo get_template_directory_uri(); ?>/humans.txt" />
<link type="text/plain" rel="author" href="<?php echo get_template_directory_uri(); ?>/robots.txt" />
<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png">
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png">
<?php wp_head(); ?>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.css" />
<script src="//cdnjs.cloudflare.com/ajax/libs/cookieconsent2/3.0.3/cookieconsent.min.js"></script>
<script>
window.addEventListener("load", function(){
window.cookieconsent.initialise({
  "palette": {
    "popup": {
      "background": "#6bb31f",
      "text": "#ffffff"
    },
    "button": {
      "background": "#ffffff",
      "text": "#6bb31f"
    }
  },
  "theme": "classic",
  "position": "bottom-right",
  "content": {
    "href": "/our-policies/"
  }
})});
</script>
</head>

<body <?php body_class(); ?>>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'starting-theme' ); ?></a>

	<header id="masthead" class="site-header" role="banner">

		<div class="container">
			<div class="row no-gutter">
				<div class="col-xs-3 col-sm-2 col-md-1 wow fadeInDown">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
						<img src="<?php echo get_template_directory_uri(); ?>/images/wilsonscountry_logo.svg" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
				</div><!-- .col-md-1 -->
				<div class="col-md-9">
					<!-- Static navbar -->
					<nav class="navbar navbar-default">
						<div class="container-fluid">
							<div class="navbar-header">


								<div id="myNav" class="overlay">
								  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
								  <div class="overlay-content">

										<div class="container">
											<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
									        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
									    <input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search-icon.svg" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
													<!-- <button type="submit" name="submit" value="submit"><img src="<?php echo get_template_directory_uri(); ?>/images/search-icon.svg" alt="submit"></button> -->
											</form>
										</div>


								  </div>
								</div>

								<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
									<span class="sr-only">Toggle navigation</span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
									<span class="icon-bar"></span>
								</button>
								<!-- Use any element to open/show the overlay navigation menu -->
								<span class="overplay-button hidden-md hidden-lg" style="" onclick="openNav()"><img src="<?php echo get_template_directory_uri(); ?>/images/search-icon-mob.svg" /></span>
							</div>
								<?php wp_nav_menu( array(
									'theme_location' => 'menu-1',
									'menu_id' => 'navbar',
									'container_id' => 'navbar',
									'container_class' => 'navbar-collapse collapse',
									'menu_class' => 'navbar-collapse',
									'items_wrap' => '<ul id="" class="nav navbar-nav navbar-right">%3$s</ul>' ) );
									?>
						</div><!--/.container-fluid -->
					</nav><!-- #site-navigation -->
				</div><!-- /.col-md-11 -->
				<div class="col-md-2 hidden-xs hidden-sm right">
					<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
			        <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search …', 'placeholder' ) ?>" value="<?php echo get_search_query() ?>" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
			    <input type="image" src="<?php echo get_template_directory_uri(); ?>/images/search-icon.svg" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
							<!-- <button type="submit" name="submit" value="submit"><img src="<?php echo get_template_directory_uri(); ?>/images/search-icon.svg" alt="submit"></button> -->
					</form>
					<a href="https://twitter.com/WilsonsCountry/" target="_blank">
						<img src="<?php echo get_template_directory_uri(); ?>/images/twitter_icon_header.svg" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
					<a href="https://www.facebook.com/WilsonsCountry/" target="_blank">
						<img src="<?php echo get_template_directory_uri(); ?>/images/facebook_icon_header.svg" alt="<?php bloginfo( 'name' ); ?>" />
					</a>
				</div>
			</div><!-- /.row -->
		</div><!-- /.container -->

	</header><!-- #masthead -->

	<div id="content" class="site-content">
