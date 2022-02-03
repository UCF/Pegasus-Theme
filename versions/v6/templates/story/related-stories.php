<?php

$related_stories = related_stories_get_stories( $post->ID );

if ( $related_stories ) :
?>
<aside class="related-stories mb-4 mb-lg-5" aria-labelledby="related-stories">
	<h2 id="related-stories" class="mb-3 mb-lg-4">Related Stories</h2>
	<?php foreach( $related_stories as $story ) : ?>
		<article class="row align-items-center position-relative mb-4">
			<?php if ( $thumbnail_src = $story['thumbnail'] ) : ?>
			<div class="col-sm-4 col-lg-12">
				<img class="img-fluid w-100 d-block mb-2 mb-sm-0 mb-lg-2" src="<?php echo $thumbnail_src; ?>" alt="" aria-hidden="true" width="300" height="200">
			</div>
			<?php endif; ?>
			<div class="col-sm-8 col-lg-12 position-static">
				<a class="stretched-link text-secondary font-weight-bold" href="<?php echo $story['url']; ?>">
					<?php echo $story['title']; ?>
				</a>
			</div>
		</article>
	<?php endforeach; ?>
</aside>
<?php endif; ?>
