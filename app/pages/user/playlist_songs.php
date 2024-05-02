<?php ob_start();
require page('includes/header'); ?>

<?php
if ($action == 'delete') {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $values = [];
        $values['music_id'] = $id_music;
        $values['playlist_id'] = $id_playlist;

        $query = "delete from title_playlist where music_id= :music_id and playlist_id= :playlist_id";
        db_query($query, $values);
        message("music is deleted successfully from your playlist");

        redirect('user/playlists');
    }
}
?>
<?php if ($action == 'delete'): ?>
    <div class="" style="max-width:500px; margin:auto;">
        <?php
        $music = get_music($id_music);
        $playlist = get_playlist($id_playlist);
        ?>
        <form action="" method="post">
            <h3>Delete music from playlist</h3>
            <?php if (!empty($music) && !empty($playlist)): ?>
                <label for="">Music title :</label>
                <div class="form-control my-1">
                    <?= set_value('title', $music['title']) ?>
                </div>
                <label for="">Playlist name :</label>
                <div class="form-control my-1">
                    <?= set_value('title', $playlist['name']) ?>
                </div>
                <button class="btn bg-danger">Delete</button>
                <a href="<?= ROOT ?>/user/playlists">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            <?php else: ?>
                <div class="alert">That record was not found</div>
                <a href="<?= ROOT ?>/user/playlists">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            <?php endif; ?>
        </form>
    </div>

<?php else: ?>
    <div>
        <div class="section-title">
            Playlist Musics
            <a href="<?= ROOT ?>/musics" class="float-end mx-4">
                <button type="button" class="btn bg-purpule">Add musics</button>
            </a>
        </div>
    </div>
    <section class="content">
        <?php
        $playlist_id = $URL[2] ?? null;
        $query = "select * from title_playlist where playlist_id= :playlist_id";
        $rows = db_query($query, ['playlist_id' => $playlist_id]);
        ?>

        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
                <?php
                $music = get_music($row['music_id']);
                ?>

                <div class="music-card">
                    <div style="overflow:hidden;">
                        <a href="<?= ROOT ?>/song/<?= $music['slug'] ?>"> <img src="<?= ROOT ?>/<?= $music['image'] ?>" alt=""></a>
                    </div>
                    <div class="card-content">
                        <div class="card-title"><?= esc($music['title']) ?></div>
                        <div class="card-subtitle">By : <?= esc(get_artist($music['artist_id'])) ?></div>
                        <div class="float-end">
                            <a href="<?= ROOT ?>/user/playlist_songs/delete/<?= $playlist_id ?>/<?= $music['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach ?>
        <?php else: ?>
            <p class="text-danger">No musics found in this playlist.</p>
        <?php endif; ?>
    </section>
<?php endif; ?>
<?php require page('includes/footer');