<?php if (function_exists('disallow_direct_load')):?>
<?php disallow_direct_load('index.php');?>
<?php get_version_header();?>

<div class="container" id="post-list">
	<div class="row">
		<div class="col-lg-9">
			<?php while(have_posts()): the_post(); ?>
			<article class="<?php echo $post->post_status; ?>">
				<h1><a href="<?php the_permalink();?>"><?php the_title();?></a></h1>
				<div class="meta">
					<span class="date"><?php the_time("F j, Y");?></span>
					<span class="author">by <?php the_author_posts_link();?></span>
				</div>
				<div class="summary">
					<?php the_excerpt();?>
				</div>
			</article>
			<?php endwhile;?>
		</div>
	</div>
</div>

<?php get_version_footer();?>
<?php endif;?>
