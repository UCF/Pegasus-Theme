<!DOCTYPE html>
<html lang="en-US">
	<head>
		<?php echo "\n".header_()."\n"?>
		<!--[if lte IE 9]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<?php if ( GTM_ID ): ?>
		<script>
			dataLayer = [];
		</script>
		<!-- Google Tag Manager -->
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','<?php echo GTM_ID; ?>');</script>
		<!-- End Google Tag Manager -->
		</script>
		<?php endif; ?>

		<?php if(GA_ACCOUNT or CB_UID):?>

		<script type="text/javascript">
			var _sf_startpt = (new Date()).getTime();
			<?php if(GA_ACCOUNT):?>

			var GA_ACCOUNT = '<?php echo GA_ACCOUNT?>';
			var _gaq = _gaq || [];
			_gaq.push(['_setAccount', GA_ACCOUNT]);
			_gaq.push(['_setDomainName', 'none']);
			_gaq.push(['_setAllowLinker', true]);
			_gaq.push(['_trackPageview']);
			<?php endif;?>
			<?php if(CB_UID):?>

			var CB_UID = '<?php echo CB_UID?>';
			var CB_DOMAIN = '<?php echo CB_DOMAIN?>';
			<?php endif?>
		</script>
		<?php endif;?>

		<script type="text/javascript">
			var IPAD_DEPLOYED = false;
			var PROTOCOL = '<?php echo is_ssl() ? "https://" : "http://"?>';
			var THEME_COMPONENTS_URL = PROTOCOL + '<?php echo str_replace(array("http://", "https://"), "", THEME_COMPONENTS_URL)?>';

			var PostTypeSearchDataManager = {
                'searches' : [],
                'register' : function(search) {
                    this.searches.push(search);
                }
            }
            var PostTypeSearchData = function(column_count, column_width, data) {
                this.column_count = column_count;
                this.column_width = column_width;
                this.data = data;
            }
		</script>

		<?php echo output_header_markup($post);?>

	</head>

	<?php $relevant_issue = get_relevant_issue($post); ?>

	<body class="<?php echo body_classes()?> <?php  if ($post->post_type == 'page' || is_404() || is_search() ) { print 'subpage'; } ?>">
		<a class="skip-navigation bg-complementary text-inverse" href="#content">Skip to main content</a>
		<div id="ucfhb" style="min-height: 50px; background-color: #000;"></div>
		<aside class="container-wide" id="pulldown" aria-labelledby="pulldown-heading">
			<div class="pulldown-container pulldown-stories">
				<div class="container">
					<div class="row">
						<div class="col-md-12 col-sm-12">
							<span class="h2 section-title" id="pulldown-heading">In This Issue</span>
						</div>
					</div>
				</div>
				<?php echo display_story_list($relevant_issue)?>
				<div class="controls">
					<a class="close pulldown-toggle" data-pulldown-container=".pulldown-stories" href="#">Close</a>
					<a class="backward icon icon-caret-left" href="#">Back</a>
					<a class="forward icon icon-caret-right" href="#">Forward</a>
				</div>
			</div>
		</aside>

		<header class="container-wide" id="header-navigation">
			<div class="container">
				<div class="row">
					<nav class="col-md-12 col-sm-12" role="navigation">
						<?php if ( is_home() ) { ?>
						<h1 class="header-logo">
							<a href="<?php echo get_site_url()?>">Pegasus</a>
						</h1>
						<?php } else { ?>
						<span class="header-logo">
							<a href="<?php echo get_site_url()?>">Pegasus</a>
						</span>
						<?php } ?>

						<ul class="navigation">
							<li id="nav-about">
								<a href="<?php echo get_permalink(get_page_by_title('About the Magazine'))?>">The Magazine of the University of Central Florida</a>
							</li>
							<li id="nav-mobile">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?php echo get_permalink($relevant_issue)?>" aria-label="Menu"></a>
							</li>
							<li id="nav-issue">
								<a class="pulldown-toggle" data-pulldown-container=".pulldown-stories" href="<?php echo get_permalink($relevant_issue)?>">In This Issue</a>
							</li>
							<li id="nav-archives">
								<a href="<?php echo get_permalink(get_page_by_title('Archives'))?>">Archives</a>
							</li>
						</ul>
					</nav>
				</div>
			</div>
		</header>

		<main id="content">
