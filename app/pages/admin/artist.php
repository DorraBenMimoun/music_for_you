<?php require page('includes/admin-header'); ?>
<center>
    <div class="section-title">Artist Profile</div>
</center>

<section class="content">

    <?php
    $id = $URL[2] ?? null;
    $query = "select * from artists where id= :id limit 1";
    $row = db_query_one($query, ['id' => $id]);
    ?>

    <?php if (!empty($row)): ?>
<!-- start artist -->
<div class="music-card-full" style="max-width: 800px; min-width:500px;">

    <h2 class="card-title"><?= esc(ucwords($row['name'])) ?></h2>

    <div style="overflow:hidden;">
        <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="">
    </div>
    <div class="card-content">
        <label>Artist Bio : </label>
        <div><?= esc($row['bio']) ?></div>
        <div>Artist Songs : </div>
        <div style="display: flex; flex:flex-wrap; justify-content: center;" >

            <?php
            $query = "select * from musics where artist_id = :artist_id  order by id desc limit 24";

            $rows = db_query($query, ['artist_id' => $row['id']]);
            ?>

            <?php if (!empty($rows)): ?>

                <?php foreach ($rows as $row): ?>
                    <?php include page('includes/song') ?>

                <?php endforeach; ?>
            <?php else: ?>
               <div class="text-danger" >No song found for this Artist.</div> 

            <?php endif; ?>
        </div>

    </div>
</div>
<!-- end artist card -->

    <?php endif; ?>



</section>

<?php require page('includes/admin-footer'); ?>