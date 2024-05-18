<?php
ob_start();

require page('includes/header'); ?>
<section class="content song-page">
    <div class="card-song-page">
<center>
    <div class="section-title">Now Playing</div>
</center>

<section class="content">

    <?php
    $slug = $URL[1] ?? null;
    $query = "select * from musics where slug= :slug limit 1";
    $row = db_query_one($query, ['slug' => $slug]);
    ?>

    <?php if (!empty($row)): ?>

        <?php include page('song-full') ?>

    <?php endif; ?>

</section>
</div>
</section>
<?php require page('includes/footer'); ?>