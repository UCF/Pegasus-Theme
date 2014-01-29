<?php disallow_direct_load('page.php');?>
<?php get_header(); the_post();?>
<section class="container<?= true ? ' ss-photo-essay' : '' ?>">
    <section class='ss-content'>
    <?php

    $slide_order = trim(get_post_meta($post->ID, 'ss_slider_slideorder', TRUE));
    $slide_order = explode(',', $slide_order);
    $captions = get_post_meta($post->ID, 'ss_slide_caption', TRUE);
    $images = get_post_meta($post->ID, 'ss_slide_image', TRUE);

    ?>

    <div class='ss-nav-wrapper' style="height: 80%;">
        <div class='ss-arrow-wrapper ss-arrow-wrapper-left'>
            <a class='ss-arrow ss-arrow-prev'>&lsaquo;</a>
        </div>
        <div class='ss-arrow-wrapper ss-arrow-wrapper-right'>
            <a class='ss-arrow ss-arrow-next' href='#2'>&rsaquo;</a>
        </div>
        <div class='ss-slides-wrapper'>

    <?php

    $data_id = 0;
    foreach ($slide_order as $s) {
        if ($s !== '') {
            $data_id++;
            $image = wp_get_attachment_image_src($images[$s], 'full');

            ?>

            <div class='ss-slide-wrapper'>
                <div class='ss-slide<?= $data_id == 1 ? ' ss-first-slide ss-current' : '' ?><?= $data_id == count($slide_order) - 1 ? ' ss-last-slide' : '' ?>' data-id='<?=$data_id; ?>' data-width='<?=$image[1]; ?>' data-height='<?=$image[2]; ?>'>
                    <img src='<?=$image[0]; ?>' />
                </div>
            </div>

            <?php
        }
    }

    ?>

    </div>
    <div class='ss-captions-wrapper' style="height: 20%;">

    <?php

    $data_id = 0;
    foreach ($slide_order as $s) {
        if ($s !== '') {
            $data_id++;

            ?>

            <div class='ss-caption <?= $data_id == 1 ? ' ss-current' : '' ?>' data-id='<?=$data_id; ?>'>
                <p><?=$captions[$s]; ?></p>
            </div>

            <?php
        }
    }
    ?>
    </section>
</section>
<?php get_footer();?>
