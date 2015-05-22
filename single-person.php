<?php disallow_direct_load( 'single.php' );?>
<?php get_header(); the_post();?>
	<div class="page-content person-profile">
		<div class="row">
			<div class="span2 details">
			<?
				$title = get_post_meta( $post->ID, 'person_jobtitle', True );
				$image_url = get_featured_image_url( $post->ID );
				$email = get_post_meta( $post->ID, 'person_email', True );
				$phones = Person::get_phones( $post );
			?>
			<img src="<?php echo $image_url ? $image_url : get_bloginfo('stylesheet_directory').'/static/img/no-photo.jpg'; ?>" />
			<? if( count( $phones ) ) { ?>
			<ul class="phones unstyled">
				<? foreach( $phones as $phone ) { ?>
				<li><?php echo $phone; ?></li>
				<? } ?>
			</ul>
			<? } ?>
			<? if( $email != '' ) { ?>
			<hr />
			<a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
			<? } ?>
			</div>
			<div class="span10">
				<h2><?php echo $post->post_title; ?><?php echo ( $title == '' ) ?: ' - '.$title; ?></h2>
				<?php echo $content = str_replace( ']]>', ']]>', apply_filters( 'the_content', $post->post_content ) ); ?>
			</div>
		</div>
		<?php get_template_part( 'includes/below-the-fold' ); ?>
	</div>
<?php get_footer();?>