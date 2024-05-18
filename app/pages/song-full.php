<?php
ob_start();

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $musicId = $row['id'];
    $playlistId = $_POST['playlist_id'] ?? null;
    $date = date("Y-m-d H:i:s");

    if (!$playlistId) {
        $errors['playlist'] = "Veuillez sélectionner une playlist.";
    } else {
        // Vérifier si la musique existe déjà dans la playlist
        $checkQuery = "SELECT * FROM title_playlist WHERE playlist_id = :playlistId AND music_id = :musicId";
        $exists = db_query($checkQuery, ['playlistId' => $playlistId, 'musicId' => $musicId]);

        if (empty($exists)) {
            $query = "insert into title_playlist (playlist_id, music_id, date) values (:playlistId, :musicId, :date)";
            db_query($query, ['playlistId' => $playlistId, 'musicId' => $musicId, 'date' => $date]);
            redirect('user/playlist_songs/' . $playlistId);
        } else {
            $errors['playlist'] = "Cette musique est déjà dans la playlist sélectionnée.";
        }
    }
}
?>



<!-- start music -->
<div class="music-card-full" style="max-width: 500px; min-width:300px;">
    <!-- Gestion des erreurs et formulaire d'ajout à la playlist -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
    <h2 class="card-title"><?= esc($row['title']) ?></h2>
    <div class="card-subtitle">By : <?= esc(get_artist($row['artist_id'])) ?></div>

    <div style="overflow:hidden;">
    <div class="container-song-img">
            <a href="<?= ROOT ?>/song/<?= $row['slug'] ?>"><img src="<?= ROOT ?>/<?= $row['image'] ?>" alt=""></a>
            <span class="overlay"></span>
        </div>

    </div>
    <div class="card-content">
        <audio controls class="audio-player">
            <source src="<?= ROOT ?>/<?= $row['file'] ?>" type="audio/mpeg">

        </audio>

        <div class="text">Date added: <?= get_date($row['date']) ?></div>

        <a href="<?= ROOT ?>/download/<?= $row['slug'] ?>">
            <button class="btn_Download">Download</button>
        </a>
        <!-- Formulaire d'ajout à la playlist -->
        <?php if (!is_admin() && !is_manager()): ?>

            <?php
            $values = [];
            $values['id_user'] = user('id');
            $query = "select * from playlists where id_user= :id_user";
            $playlists = db_query($query, $values);
            ?>
            <?php if (!empty($playlists)): ?>

                <form action="" method="post">


                    <div style="margin-top: 20px; display: flex; align-items: center; gap: 20px; flex-direction: column;">

                        <select name="playlist_id" class="form-select">
                            <option value="">Select playlist</option>
                            <?php foreach ($playlists as $playlist): ?>
                                <option <?= set_select('playlist_id', $playlist['id']) ?> value='<?= $playlist['id'] ?>'>
                                    <?= $playlist['name'] ?>
                                </option>";
                            <?php endforeach ?>


                        </select>

                        <button type="submit" class="btn_download">Ajouter à la playlist</button>

                    </div>
                </form>
            <?php else: ?>
                <p class="text"> No playlist available. Please create one</p>
                <a href="<?= ROOT ?>/user/playlists/add">

                    <button class=" btn_Download">Add new</button>
                </a>

            <?php endif ?>
        <?php endif; ?>

    </div>
</div>
<!-- end music card -->