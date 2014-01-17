<?php disallow_direct_load('page.php');?>
<?php get_header(); the_post();?>
<div class="container">
	<?php the_content(); ?>
</div>
<?php get_footer();?>