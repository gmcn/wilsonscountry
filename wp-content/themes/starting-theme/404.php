<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Starting_Theme
 */

get_header(); ?>

<?php
include(locate_template("inc/page-elements/breadcrumbs.php"));

?>

<div class="container-fluid intro">
	<?php if ($introbg) : ?>
		<div class="container imgbg" style="background: url(<?php echo $introbg ?>) center top no-repeat; background-size: cover;">
		</div>
	<?php endif; ?>
	<div class="container">

		<div class="row">

			<h1><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'starting-theme' ); ?></h1>
			<h2>
				It looks like nothing was found at this location.
			</h2>

			<p>

			</p>
		</div>

	</div>
</div>

<?php
get_footer();
