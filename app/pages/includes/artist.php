
<!-- start music card -->
<div class="music-card">
    <div style="overflow:hidden;">
        <a href="<?= ROOT ?>/artist/<?=$row['id']?>"> <img src="<?= ROOT ?>/<?=$row['image']?>" alt=""></a>
    </div>
    <div class="card-content">
        <div class="card-title"><?=esc(ucwords($row['name']))?></div>
    </div>
</div>
<!-- end music card -->