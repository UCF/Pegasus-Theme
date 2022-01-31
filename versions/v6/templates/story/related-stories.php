<?php

$related_stories = related_stories_get_stories( $post->ID );

if ( $related_stories ) :
?>
<aside>
	<h2>Related Stories</h2>
	<?php foreach( $related_stories as $story ) : ?>
		<article class="d-block mb-4">
				<img class="img-fluid" src="<?php echo $story['thumbnail']; ?>">
				<a class="h6 text-secondary stretched-link" href="<?php echo $story['url']; ?>"><?php echo $story['title']; ?></a>
				<p class="small"><?php echo $story['deck']; ?></p>
		</article>
	<?php endforeach; ?>
</aside>
<?php endif; ?>
