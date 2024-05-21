<!-- start music card -->
<div class="music-card">
    <div class="img-content">
        <a href="<?= ROOT ?>/song/<?=$row['slug']?>"> <img src="<?= ROOT ?>/<?=$row['image']?>" alt=""></a>
    </div>
    <div class="text-content">
        <div class="card-title"  style="font-size: 24px!important;"><?=esc($row['title'])?></div>
        <div class="card-subtitle">By : <?=esc(get_artist($row['artist_id']))?></div>
    
    </div>
    <img  class="card-vinyl" src="../../public/assets/images/Vinyl.svg" alt="Vinyl">
    <span class="trey"></span>
</div>
<!-- end music card -->