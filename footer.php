		</div><!-- close #body_content or last body div -->
		
		<!--
			If this is an issue cover from before Spring 2014,
			or if this is any single story,
			use get_navigation_stories().

			Otherwise, use new [read-on] section output.
		-->

		<div class="container-wide" id="footer-navigation">
			<div class="container">
				<div class="row">
					<footer class="span12">
						<span class="footer-logo <?php if(ipad_deployed()) { ?>pull-left<?php } ?>">
							<a class="sprite logo-large-white <?php if(ipad_deployed()) { ?>pull-right<?php } ?>" href="<?=get_site_url()?>">
								Pegasus Magazine
							</a>
						</span>

						<? if(ipad_deployed()) {?>
						<span class="footer-ipad-app pull-right">
							<a class="sprite ipad-app-btn pull-left" href="<?=get_theme_option('ipad_app_url')?>">
								Download on the App Store
							</a>
						</span>
                        <?}?>
						
						<?php
						$defaults = array(
							'theme_location'  => 'footer-menu',
							'container'       => false,
							'menu_class'      => 'navigation',
						);

						wp_nav_menu( $defaults );
						?>
						<p class="copyright">
							&copy; <?=date('Y')?> University of Central Florida.
						</p>
					</footer>
				</div>
			</div>
		</div>
	</body>
	<?="\n".footer_()."\n"?>
</html>