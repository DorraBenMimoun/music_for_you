<!-- start music -->
<div class="music-card-full" style="max-width: 800px; min-width:500px;">

    <h2 class="card-title"><?= esc($row['title']) ?></h2>
    <div class="card-subtitle">By : <?= esc(get_artist($row['artist_id'])) ?></div>

    <div style="overflow:hidden;">
        <a href="<?= ROOT ?>/song/<?= $row['slug'] ?>"> <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt=""></a>
    </div>
    <div class="card-content">
        <audio controls style="width:100%" >
            <source src="<?= ROOT ?>/<?= $row['file'] ?>" type="audio/mpeg">
        </audio>
        
        <div>Date added: <?=get_date($row['date'])?></div>

        <a href="<?=ROOT?>/download/<?=$row['slug']?>">
        <button class="btn bg-purpule" >Download</button>
        </a>
    </div>
</div>
<!-- end music card -->