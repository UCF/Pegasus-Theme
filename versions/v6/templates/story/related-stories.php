<?php

$related_stories = related_stories_get_stories( $post->ID );

if ( $related_stories ) :
?>
<aside aria-labelledby="related-stories">
	<h2 id="related-stories">Related Stories</h2>
	<?php foreach( $related_stories as $story ) : ?>
		<article class="d-block mb-5 position-relative">
			<img alt="" class="img-fluid" src="<?php echo $story['thumbnail']; ?>">
			<a class="h6 text-secondary stretched-link" href="<?php echo $story['url']; ?>"><?php echo $story['title']; ?></a>
		</article>
	<?php endforeach; ?>
</aside>
<?php endif; ?>
