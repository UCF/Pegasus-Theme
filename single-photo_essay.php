<?php disallow_direct_load('page.php');?>
<?php get_header(); the_post();?>
<article class="container-wide<?= true ? ' ss-photo-essay' : '' ?>">
    <section class='ss-content'>
        <?php

        $slide_order = trim(get_post_meta($post->ID, 'ss_slider_slideorder', TRUE));
        // Get rid of blank array entries
        $slide_order = array_filter( explode(',', $slide_order), 'strlen' );
        $captions = get_post_meta($post->ID, 'ss_slide_caption', TRUE);
        $titles = get_post_meta($post->ID, 'ss_slide_title', TRUE);
        $images = get_post_meta($post->ID, 'ss_slide_image', TRUE);
        ?>

        <div class='ss-nav-wrapper' style="height: <?php if (array_filter($captions)) { ?>80%<?php } else { ?>100%<?php } ?>;">
            <div class='ss-arrow-wrapper ss-arrow-wrapper-left'>
                <a class='ss-arrow ss-arrow-prev ss-last'><div>&lsaquo;</div></a>
            </div>
            <div class='ss-arrow-wrapper ss-arrow-wrapper-right'>
                <a class='ss-arrow ss-arrow-next' href='#2'><div>&rsaquo;</div></a>
            </div>
            <div class='ss-slides-wrapper'>

            <?php

            $slide_count = count($slide_order);
            $ss_half = floor($slide_count/2) + 1;
            $end = false;
            $i = $ss_half;
            while ($end == false) {
                if ($i == $slide_count) {
                    $i = 0;
                }

                if ($i == $ss_half - 1) {
                    $end = true;
                }

                $s = intval( $slide_order[$i] );
                $image_id = intval( $images[$s] );
                // TODO sometimes attachments fail to save _wp_attached_file meta val
                // on upload and cause wp_get_attachment_image_src to fail, why?
                $image = wp_get_attachment_image_src( $image_id, 'full' );
                if ( $image === false ) {
                	$image[0] = wp_get_attachment_url( $image_id );
                	@list( $width, $height ) = getimagesize( $image[0] );
                	$image[1] = $width;
                	$image[2] = $height;
                }
                ?>
                <div class="ss-slide-wrapper">
                    <div class="ss-slide<?= $i == 0 ? ' ss-first-slide ss-current' : '' ?><?= $i == $slide_count - 1 ? ' ss-last-slide' : '' ?>" data-id="<?=$i + 1?>" data-width="<?=$image[1]?>" data-height="<?=$image[2]?>">
                        <img src="<?=$image[0]; ?>" alt="<?=$titles[$s]; ?>" />
                    </div>
                </div>
            <?php
                $i++;
            }
            ?>
            </div>

            <?php
            // Make sure at least one caption exists before adding caption wrapper.
            if (array_filter($captions)) { ?>
            <div class='ss-captions-wrapper' style="height: 20%;">
            <?php
                $data_id = 0;
                foreach ($slide_order as $s) {
                    if ($s !== '') {
                        $data_id++;

                        ?>

                        <div class='ss-caption <?= $data_id == 1 ? ' ss-current' : '' ?>' data-id='<?=$data_id; ?>'>
                            <p class="caption"><?=$captions[$s]; ?></p>
                        </div>

                        <?php
                    }
                }
            }
            ?>
            </div>


            <div class="ss-closing-overlay" style="display: none;">
                <div class="ss-slide" data-id="restart-slide">
                    <a class="ss-control ss-restart" href="#1"><i class="repeat-alt-icon"></i><div>REPLAY:</div></a>
                    <? if ($is_fullscreen): ?><div class="ss-title"><?=$post->post_title; ?></div><?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</article>
<?php get_footer();?>
