<?php
 /*-- ---------ADD ------------ --*/

if ($action == 'add') {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $errors = [];

        //data validation
        if (empty($_POST["name"])) {
            $errors['name'] = "Name is required.";
        }
        if (empty($_POST["description"])) {
            $errors['description'] = "Description is required.";
        }
        if (empty($_POST["visibilite"])) {
            $errors['visibilite'] = "Visibility selection is required.";
        }

        // Traitement de l'image uploadée
        if (!empty($_FILES['image']['name'])) {
            $folder = "uploads/playlists/images/";
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
                file_put_contents($folder . "index.php", "");
            }
            $allowed = ['image/jpeg', 'image/png'];
            if ($_FILES['image']['error'] == 0 && in_array($_FILES['image']['type'], $allowed)) {
                $destination_image = $folder . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], $destination_image);
            } else {
                $errors['image'] = "Invalid image format. Allowed formats are JPEG, PNG, GIF.";
            }
        } else {
            $errors['image'] = 'An image is required.';
        }
        if (empty($errors)) {
            $values = [];
            $values['name'] = trim($_POST['name']);
            $values['description'] = trim($_POST['description']);
            $values['visibilite'] = trim($_POST['visibilite']);
            $values['date'] = date("Y-m-d H:i:s");
            $values['image'] = $destination_image;
            $values['id_user'] = user('id');

            $query = "insert into playlists (name, image, date, description, visibilite, id_user) VALUES (:name, :image, :date, :description, :visibilite, :id_user)";
            db_query($query, $values);
            message("playlist created successfully");
            redirect('user/playlists');

        }
    }
} else
 /*-- ---------EDIT ------------ --*/

    if ($action == 'edit') {
        $query = "select * from playlists where id = :id_playlist limit 1";
        $row = db_query_one($query, ['id_playlist' => $id_playlist]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];

            //data validation
            if (empty($_POST["name"])) {
                $errors['name'] = "Name is required.";
            }
            if (empty($_POST["description"])) {
                $errors['description'] = "Description is required.";
            }
            if (empty($_POST["visibilite"])) {
                $errors['visibilite'] = "Visibility selection is required.";
            }
            if (!empty($_FILES['image']['name'])) {
                $folder = "uploads/playlists/images/";
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                    file_put_contents($folder . "index.php", "");
                }
                $allowed = ['image/jpeg', 'image/png', 'image/gif'];
                if ($_FILES['image']['error'] == 0 && in_array($_FILES['image']['type'], $allowed)) {
                    $destination_image = $folder . $_FILES['image']['name'];
                    move_uploaded_file($_FILES['image']['tmp_name'], $destination_image);
                    if (file_exists($row['image'])) {
                        unlink($row['image']); // Supprimer l'ancienne image
                    }
                } else {
                    $errors['image'] = "Invalid image format. Allowed formats are JPEG, PNG, GIF.";
                }
            }
            if (empty($errors)) {
                $values = [];
                $values['name'] = trim($_POST['name']);
                $values['description'] = trim($_POST['description']);
                $values['visibilite'] = trim($_POST['visibilite']);

                $values['id'] = $id_playlist;


                $query = "update playlists set description = :description, name = :name, visibilite= :visibilite ";
                if (!empty($destination_image)) {
                    $query .= ",image= :image ";
                    $values['image'] = $destination_image;
                }
                $query .= " where id = :id limit 1 ";

                db_query($query, $values);
                message("playlist edited successfully");
                redirect('user/playlists');
            }
        }
    } else
            /*-- ---------DELETE ------------ --*/

        if ($action == 'delete') {
            $query = "select * from playlists where id = :id limit 1";
            $row = db_query_one($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $errors = [];

                if (empty($errors)) {
                    $values = [];
                    $values['id'] = $id;

                    $query = "delete from playlists where id= :id limit 1";

                    db_query($query, $values);
                    message("playlist deleted successfully");
                    redirect('/user/playlists');
                }
            }
        }
?>



<?php require page('includes/header') ?>
    <!-- ---------ADD ------------ -->

<section class="admin-content" style="min-height:80vh;">
    <?php if ($action == 'add'): ?>

        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Add new Playlist</h3>
                <div>

                    <input class="form-control my-1" type="text" name="name" value="<?= set_value('name') ?>"
                        placeholder="name">
                    <?php if (!empty($errors['name'])): ?>
                        <small class="text-danger"><?= $errors['name'] ?></small>
                    <?php endif; ?>
                </div>
                <div>
                    <input class="form-control my-1" type="text" name="description" value="<?= set_value('description') ?>"
                        placeholder="Description">
                    <?php if (!empty($errors['description'])): ?>
                        <small class="text-danger"><?= $errors['description'] ?></small>
                    <?php endif; ?>
                </div>
                <div>
                    <select name="visibilite" id="visibilite" class="form-select my-1">
                        <option value="">--Select visibilite--</option>
                        <option <?= set_select('visibilite', 'public') ?> value="public">Public</option>
                        <option <?= set_select('visibilite', 'private') ?> value="private">Private</option>

                    </select>
                    <?php if (!empty($errors['visibilite'])): ?>
                        <small class="text-danger"><?= $errors['visibilite'] ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-control m-1">
                    <div>Image :</div>
                    <input type="file" class="form-control my-1" name="image">
                    <?php if (!empty($errors['image'])): ?>
                        <small class="text-danger"><?= $errors['image'] ?></small>
                    <?php endif; ?>
                </div>

                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/user/playlists">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            </form>
        </div>
        <!-- ---------EDIT ------------ -->

    <?php elseif ($action == 'edit'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Edit Playlist</h3>

                <?php if (!empty($row)): ?>

                    <input class="form-control my-1" type="text" name="name" value="<?= set_value('name', $row['name']) ?>"
                        placeholder="Name">
                    <?php if (!empty($errors['name'])): ?>
                        <small class="text-danger"><?= $errors['name'] ?></small>
                    <?php endif; ?>
                    <input class="form-control my-1" type="text" name="description"
                        value="<?= set_value('description', $row['description']) ?>" placeholder="Description">
                    <?php if (!empty($errors['description'])): ?>
                        <small class="text-danger"><?= $errors['description'] ?></small>
                    <?php endif; ?>
                    <select name="visibilite" id="visibilite" class="form-select my-1">
                        <option value="">--Select Role--</option>
                        <option <?= set_select('visibilite', 'public', $row['visibilite']) ?> value="public">Public</option>
                        <option <?= set_select('visibilite', 'private', $row['visibilite']) ?> value="private">Private</option>

                    </select>

                    <?php if (!empty($errors['visibilite'])): ?>
                        <small class="text-danger"><?= $errors['visibilite'] ?></small>
                    <?php endif; ?>
                    <div class="form-control m-1">
                        <div>Image :</div>
                        <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="" style="width:200px; height:200px; object-fit:cover;">

                        <input type="file" class="form-control my-1" name="image">
                        <?php if (!empty($errors['image'])): ?>
                            <small class="text-danger"><?= $errors['image'] ?></small>
                        <?php endif; ?>
                    </div>

                    <button class="btn bg-orange">Save</button>
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
        <!-- ---------DELETE ------------ -->

    <?php elseif ($action == 'delete'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Delete User</h3>

                <?php if (!empty($row)): ?>

                    <div class="form-control my-1">
                        <?= set_value('name', $row['name']) ?>
                    </div>
                    <?php if (!empty($errors['name'])): ?>
                        <small class="text-danger"><?= $errors['name'] ?></small>
                    <?php endif; ?>
                    <div class="form-control my-1">
                        <?= set_value('description', $row['description']) ?>
                    </div>
                    <div class="form-control my-1">
                        <?= set_value('visibilite', $row['visibilite']) ?>
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
        <?php
        $id_user = user('id');
        $query = "select * from playlists where id_user= :id_user ";
        $rows = db_query($query, ["id_user" => $id_user]);
        ?>


        <h3>Playlists
            <a href="<?= ROOT ?>/user/playlists/add">

                <button class=" float-end btn bg-purpule">Add new</button>
            </a>
        </h3>
        <table class="table text-center">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Image</th>
                <th>Description</th>
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
                        <td><?= $row['visibilite'] ?></td>
                        <td><?= get_date($row['date']) ?></td>
                        <td>
                            <a href="<?= ROOT ?>/user/playlist_songs/<?= $row['id'] ?>">
                                details
                            </a>
                            <a href="<?= ROOT ?>/user/playlists/edit/<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                </svg>
                            </a>
                            <a href="<?= ROOT ?>/user/playlists/delete /<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-danger">No playlists found.</td>
                </tr> <?php endif; ?>
        </table>
    <?php endif; ?>
</section>
<?php require page('includes/footer'); ?>