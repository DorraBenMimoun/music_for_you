<?php

if ($action == 'add') {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $errors = [];

        //data validation
        if (empty($_POST["name"])) {
            $errors['name'] = "a name is required";
        } else
            if (!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['name'])) {
                $errors['name'] = "a name can only have letters and spaces";

            }
        //image
        if (!empty($_FILES['image']['name'])) {

            $folder = "uploads/";
            if (!file_exists($folder)) {
                mkdir($folder, 077, true);
                file_put_contents($folder . "index.php", "");

            }
            $allowed = ['images/jpeg', 'images/png'];
            echo $_FILES['image']['error'];
            //&& in_array($_FILES['image']['type'], $allowed)
            if ($_FILES['image']['error'] == 0) {


                $destination = $folder . $_FILES['image']['name'];

                move_uploaded_file($_FILES['image']['tmp_name'], $destination);

            } else {

                $errors['image'] = "Image no valid. Allowed types are " . implode(",", $allowed);

            }

        } else {
            $errors['image'] = 'an image is required';
        }
        if (empty($errors)) {
            $values = [];
            $values['name'] = trim($_POST['name']);
            $values['bio'] = trim($_POST['bio']);
            $values['image'] = $destination;
            $values['user_id'] = user('id');



            $query = "insert into artists(name,bio,image,user_id) values(:name,:bio,:image,:user_id)";
            db_query($query, $values);
            message("name created successfully");
            redirect('admin/artists');

        }
    }
} else
    if ($action == 'edit') {
        $query = "select * from artists where id = :id limit 1";
        $row = db_query_one($query, ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];

            //data validation
            if (empty($_POST["name"])) {
                $errors['name'] = "a name is required";
            } else {
                if (!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['name'])) {
                    $errors['name'] = "a name can only have letters with no spaces";

                }
            }

            //image

            if (!empty($_FILES['image']['name'])) {

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 077, true);
                    file_put_contents($folder . "index.php", "");

                }
                $allowed = ['images/jpeg', 'images/png'];


                if ($_FILES['image']['error'] == 0) {

                    $destination = $folder . $_FILES['image']['name'];

                    move_uploaded_file($_FILES['image']['tmp_name'], $destination);
                    //delete old file
                    if (file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                } else {

                    $errors['name'] = "Image no valid. Allowed types are " . implode(",", $allowed);

                }

            }

            if (empty($errors)) {
                $values = [];
                $values['name'] = trim($_POST['name']);
                $values['user_id'] = user('id');
                $values['bio'] = trim($_POST['bio']);
                $values['id'] = $id;
                $query = "update artists set  name = :name, user_id= :user_id, bio= :bio where id= :id limit 1";

                if (!empty($destination)) {
                    $query = "update artists set  name = :name, image= :image, user_id= :user_id, bio= :bio where id= :id limit 1";

                    $values['image'] = $destination;
                }
                if (file_exists($row['image'])) {
                    unlink($row['image']);
                }
                db_query($query, $values);
                message("artist edited successfully");
                redirect('admin/artists');
            }
        }



    } else
        if ($action == 'delete') {
            $query = "select * from artists where id = :id limit 1";
            $row = db_query_one($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $errors = [];

                if (empty($errors)) {


                    $values = [];
                    $values['id'] = $id;


                    $query = "delete from artists where id= :id limit 1";

                    db_query($query, $values);
                    //delete image
                    if (file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                    message("name deleted successfully");
                    redirect('admin/artists');

                }
            }
        }



?>



<?php require page('includes/admin-header') ?>

<section class="admin-content" style="min-height:200px;">
    <?php if ($action == 'add'): ?>

        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Add new Artist</h3>

                <input class="form-control my-1" type="text" name="name" value="<?= set_value('name') ?>"
                    placeholder="Artist name">
                <?php if (!empty($errors['name'])): ?>
                    <small class="text-danger"><?= $errors['name'] ?></small>
                <?php endif; ?>
                <div>Artist Image : </div>

                <input type="file" class="form-control my-1" name="image">
                <?php if (!empty($errors['image'])): ?>
                    <small class="text-danger"><?= $errors['image'] ?></small>
                <?php endif; ?>
                <label>Artist Bio : </label>
                <textarea name="bio" id="bio" rows="10" class="form-control my-1"><?= set_value('bio') ?></textarea>
                <?php if (!empty($errors['image'])): ?>
                    <small class="text-danger"><?= $errors['image'] ?></small>
                <?php endif; ?>

                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/admin/artists">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            </form>
        </div>
    <?php elseif ($action == 'edit'): ?>



        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Edit Artist</h3>

                <?php if (!empty($row)): ?>

                    <input class="form-control my-1" type="text" name="name" value="<?= set_value('name', $row['name']) ?>"
                        placeholder="name">
                    <?php if (!empty($errors['name'])): ?>
                        <small class="text-danger"><?= $errors['name'] ?></small>
                    <?php endif; ?>

                    <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="" style="width:200px; height:200px; object-fit:cover;">
                    <div>Artist Image : </div>
                    <input type="file" class="form-control my-1" name="image">

                    <label>Artist Bio : </label>
                    <textarea name="bio" id="bio" rows="10"
                        class="form-control my-1"><?= set_value('bio', $row['bio']) ?></textarea>

                    <button class="btn bg-orange">Save</button>
                    <a href="<?= ROOT ?>/admin/artists">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/admin/artists">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    <?php elseif ($action == 'delete'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Delete Artist</h3>

                <?php if (!empty($row)): ?>

                    <div class="form-control my-1">
                        <?= set_value('name', $row['name']) ?>
                    </div>
                    <?php if (!empty($errors['name'])): ?>
                        <small class="text-danger"><?= $errors['name'] ?></small>
                    <?php endif; ?>

                    <button class="btn bg-danger">Delete</button>
                    <a href="<?= ROOT ?>/admin/artists">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/admin/artists">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    <?php else: ?>
        <?php
        $query = "select * from artists order by id desc ";
        $rows = db_query($query);
        ?>


        <h3>Artists
            <a href="<?= ROOT ?>/admin/artists/add">

                <button class=" float-end btn bg-purpule">Add new</button>
            </a>
        </h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Artist</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></th>
                        <td><?= $row['name'] ?></td>
                        <td>
                            <a href="<?= ROOT ?>/admin/artist/<?= $row['id'] ?>">
                                <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt=""
                                    style="width:100px; height:100px; object-fit:cover;">
                            </a>
                        </td>

                        <td>
                            <a href="<?= ROOT ?>/admin/artists/edit/<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                </svg>
                            </a>
                            <a href="<?= ROOT ?>/admin/artists/delete /<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                    <path
                                        d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>

        </table>

    <?php endif; ?>




</section>
<?php require page('includes/admin-footer'); ?>