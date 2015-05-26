<?php @header("HTTP/1.1 404 Not found", true, 404);?>
<?php disallow_direct_load('404.php');?>

<?php get_version_header(); the_post();?>

<section class="container" id="search-results">
	<div class="row">
		<div class="col-md-12">
			<h1>Page Not Found</h1>
			<p><big>
				The page you were looking for was not found. Please check the address and try again. If you believe you reached
				this page in error, please contact the Web Communications Team at
				<a href="mailto:webcom@ucf.edu">webcom@ucf.edu</a>.
			</big></p>
		</div>
	</div>
</section>
<?php get_version_footer();?>
