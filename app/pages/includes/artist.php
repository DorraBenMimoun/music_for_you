<!-- start music card -->
<div class="music-card">
    <div>
        <a href="<?= ROOT ?>/artist/<?= $row['id'] ?>"> <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt=""></a>
    </div>
    <div class="card-content">
        <div class="card-title"><?= esc(ucwords($row['name'])) ?></div>
        <?php if (!empty($row['bio'])): ?>
            <div class="card-subtitle">
                <p><b class="t">Biographie :</b><br>
                    <?= esc($row['bio']) ?></p>
            </div>
        <?php endif; ?>

    </div>
</div>
<!-- end music card -->