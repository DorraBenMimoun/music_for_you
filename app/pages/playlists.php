<?php require page('includes/header') ?>

<section class="admin-content" style="min-height:200px;">

    <?php
    $query = "select * from playlists where visibilite like 'public'";
    $rows = db_query($query);
    ?>

    <h3>Playlists
        <a href="<?= ROOT ?>/user/playlists/add">

            <button class=" float-end btn bg-purpule">Add my own playlist</button>
        </a>
    </h3>
    <table class="table text-center">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Image</th>
            <th>Description</th>
            <th>Owner</th>
            <th>Visibilite</th>
            <th>Date</th>
            <th>Action</th>
        </tr>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></th>
                    <td><?= $row['name'] ?></td>
                    <td><img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="" style="width:100px; height:100px; object-fit:cover;">
                    <td><?= $row['description'] ?></td>
                    <td><?= get_user_name($row['id_user']) ?></td>
                    <td><?= $row['visibilite'] ?></td>
                    <td><?= get_date($row['date']) ?></td>
                    <td>
                        <a href="<?= ROOT ?>/user/playlist_songs/<?= $row['id'] ?>">
                            details
                        </a>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="7" class="text-center text-danger">No playlists found.</td>
            </tr>
        <?php endif; ?>
    </table>
</section>
<?php require page('includes/footer'); ?>