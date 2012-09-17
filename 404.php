<?php @header("HTTP/1.1 404 Not found", true, 404);?>
<?php disallow_direct_load('404.php');?>

<?php get_header(); the_post();?>
<div class="row">
	<div class="span12" style="margin-bottom:200px;">
		<h2><big>Page Not Found</big></h2>
		<p><big>The page you were looking for was not found. Please check the address and try again. If you believe you reached this page in error, please contact the Web Communications Team at <a href="mailto:webcom@ucf.edu">webcom@ucf.edu</a>.</big></p>
	</div>
</div>
<?php get_footer();?>