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
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'starting-theme' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="site-branding">
			<?php
			if ( is_front_page() && is_home() ) : ?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
			<?php else : ?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
			<?php
			endif;

			$description = get_bloginfo( 'description', 'display' );
			if ( $description || is_customize_preview() ) : ?>
				<p class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></p>
			<?php
			endif; ?>
		</div><!-- .site-branding -->

		<!-- Static navbar -->
	      <nav class="navbar navbar-default">
	        <div class="container-fluid">
	          <div class="navbar-header">
	            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	              <span class="sr-only">Toggle navigation</span>
	              <span class="icon-bar"></span>
	              <span class="icon-bar"></span>
	              <span class="icon-bar"></span>
	            </button>
	          </div>
	            <?php wp_nav_menu( array(
								'theme_location' => 'menu-1',
								'menu_id' => 'navbar',
								'container_id' => 'navbar',
								'container_class' => 'navbar-collapse collapse',
								'menu_class' => 'navbar-collapse',
								'items_wrap' => '<ul id="" class="nav navbar-nav navbar-right">%3$s</ul>' ) );
								?>
	            <ul class="nav navbar-nav navbar-right">
	              <li class="active"><a href="./">Default <span class="sr-only">(current)</span></a></li>
	              <li><a href="../navbar-static-top/">Static top</a></li>
	              <li><a href="../navbar-fixed-top/">Fixed top</a></li>
	            </ul>
	        </div><!--/.container-fluid -->
	      </nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
