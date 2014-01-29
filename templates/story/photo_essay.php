<?php disallow_direct_load('default.php');?>

<h1><?=$post->post_title?></h1>
<article class="<?= true ? ' ss-photo-essay' : '' ?>">
    <?=the_content()?>
</article>
