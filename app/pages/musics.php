<?php require page('includes/header'); ?>

<div class="section-title">Music</div>

<section class="content">

    <?php
    $rows = db_query(("select * from musics order by id desc limit 24"));
    ?>

    <?php if (!empty($rows)): ?>

        <?php foreach ($rows as $row): ?>
            <?php include page('includes/song') ?>

        <?php endforeach; ?>

    <?php endif; ?>


</section>



<?php require page('includes/footer'); ?>