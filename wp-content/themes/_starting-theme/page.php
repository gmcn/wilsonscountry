<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Starting_Theme
 */

get_header(); ?>

	<?php
	include(locate_template("inc/page-elements/breadcrumbs.php"));
	include(locate_template("inc/page-elements/intro.php"));

	?>

	<?php include(locate_template("inc/page-about/content.php")); ?>

	<?php if (is_page( 'recipes' )) : ?>

		<?php include(locate_template("inc/page-recipes/recipes.php")); ?>

	<?php elseif (is_page( 'careers' )) : ?>

		<?php include(locate_template("inc/page-careers/careers.php")); ?>

	<?php elseif (is_page( 'our-policies' )) : ?>

		<?php include(locate_template("inc/page-policies/content.php")); ?>

	<?php elseif (is_page( 'news-blog' )) : ?>

		<?php include(locate_template("inc/page-blog/blog.php")); ?>

	<?php endif ?>

<?php
//get_sidebar();
get_footer();
