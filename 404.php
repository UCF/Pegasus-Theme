<?php @header("HTTP/1.1 404 Not found", true, 404);?>
<?php disallow_direct_load('404.php');?>

<?php get_header(); the_post();?>
<h1>Page Not Found</h1>
<p><big>The page you were looking for was not found. Please check the address and try again. If you believe you reached this page in error, please contact the Web Communications Team at <a href="mailto:webcom@ucf.edu">webcom@ucf.edu</a>.</big></p>
<?php get_footer();?>