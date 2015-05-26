<?php disallow_direct_load('page.php');?>
<?php get_version_header(); the_post();?>
<section class="container">
	<?php the_content(); ?>
</section>
<?php get_version_footer();?>
