<?php $options = get_option(THEME_OPTIONS_NAME);?>
<?php if ($options['enable_google'] or $options['enable_google'] === null):?>
<?php
	$domain  = $options['search_domain'];
	$limit   = (int)$options['search_per_page'];
	$start   = (is_numeric($_GET['start'])) ? (int)$_GET['start'] : 0;
	$results = get_search_results($_GET['s'], $start, $limit, $domain);
?>
<?php get_version_header(); ?>

<section class="container" id="search-results">
	<div class="row">
		<div class="span10 offset1">
			<h1>Search Results</h1>
			<?=get_search_form()?>

			<?php if(count($results['items'])):?>
			<ul class="result-list">
				<?php foreach($results['items'] as $result):?>
				<li class="item">
					<h2>
						<a class="<?=mimetype_to_application(($result['mime']) ? $result['mime'] : 'text/html')?>" href="<?=$result['url']?>">
							<?php if($result['title']):?>
							<?=$result['title']?>
							<?php else:?>
							<?=substr($result['url'], 0, 45)?>...
							<?php endif;?>
						</a>
					</h2>
					<a href="<?=$result['url']?>" class="ignore-external url sans"><?=$result['url']?></a>
					<div class="snippet">
						<?=str_replace('<br>', '', $result['snippet'])?>
					</div>
				</li>
			<?php endforeach;?>
			</ul>

			<?php if($start + $limit < $results['number']):?>
			<a class="button more" href="./?s=<?=$_GET['s']?>&amp;start=<?=$start + $limit?>">More Results</a>
			<?php endif;?>

			<?php else:?>

			<p>No results found for "<?=htmlentities($_GET['s'])?>".</p>

			<?php endif;?>
		</div>
	</div>
</section>

<?php else:?>
<?php get_version_header(); ?>
<?php
	// Our Loop gets screwy here probably because a global $post is set.  Redo the Loop.
	$search = new WP_Query(array(
		'numberposts' => -1,
		'post_type' => array('page', 'story', 'issue'),
		's' => htmlentities($_GET['s']),
	));
?>

<section class="container" id="search-results">
	<div class="row">
		<div class="span10 offset1">
			<h1>Search Results</h1>
			<?=get_search_form()?>

			<?php if($search->have_posts()):?>
				<ul class="result-list">
				<?php while($search->have_posts()): $search->the_post();?>
					<li class="item">
						<h2><a href="<?php the_permalink();?>"><?php the_title();?></a></h2>
						<a href="<?php the_permalink();?>"><?php the_permalink();?></a>
						<div class="snippet">
							<?php the_excerpt();?>
						</div>
					</li>
				<?php endwhile;?>
				</ul>
			<?php else:?>
				<p>No results found for "<?=htmlentities($_GET['s'])?>".</p>
			<?php endif;?>
		</div>
	</div>
</section>

<?php get_version_footer();?>
<?php endif;?>
