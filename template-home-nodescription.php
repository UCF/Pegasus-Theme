<div class="container wide" id="home_header">
			<div class="row">
				<div class="span12">
					<div class="row" style="position:relative;">
						<a href="<?=site_url()?>" class="span3 title">
							PEGASUS
						</a>

						<div class="span4 offset5 description">
							The Magazine of the University of Central Florida
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="span5 edition">
					Summer 2012
				</div>
			</div>
		</div>
<div class="container">
	<?php $options = get_option(THEME_OPTIONS_NAME);?>
	<?php $page    = get_page_by_title('Home');?>
	<div class="row page-content nodescription" id="home" data-template="home-nodescription">

		<?php $content = str_replace(']]>', ']]&gt;', apply_filters('the_content', $page->post_content));?>
		<?php if($content):?>
		<?=$content?>
		<?php endif; ?>
	
		
	</div>

<?php get_footer();?>
<style type="text/css">
	body, #footer { 
		background: #031123;
	}
	#footer_navigation {
		border-top: 1px solid #3f3d3d;
	}
</style>