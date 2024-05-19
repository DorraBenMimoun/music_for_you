<!-- start artist -->
<div class="music-card-full" style="min-width:500px;">

    <h2 class="card-title"><?= esc($row['name']) ?></h2>

    <div >
        <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="">
    </div>
    <div class="card-content">
        <?php if(!empty($row['bio'])): ?>
        <label>Artist Bio : </label>
        <div><?= esc($row['bio']) ?></div>
        <?php endif; ?>

        <div>Artist Songs : </div>
        <div style="display: flex; flex:flex-wrap; justify-content: center;">

            <?php
            $query = "select * from musics where artist_id = :artist_id  order by id desc limit 24";

            $rows = db_query($query, ['artist_id' => $row['id']]);
            ?>

            <?php if (!empty($rows)): ?>

                <?php foreach ($rows as $row): ?>
                    <?php include page('includes/song') ?>

                <?php endforeach; ?>

            <?php endif; ?>
        </div>

    </div>
</div>
<!-- end artist card -->