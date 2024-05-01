<?php

if ($action == 'add') {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $errors = [];

        //data validation
        if (empty($_POST["title"])) {
            $errors['title'] = "a title is required";
        } else
            if (!preg_match("/^[a-zA-Z0-9 \.\&\-]+$/", $_POST['title'])) {
                $errors['title'] = "a title can only have letters and spaces";

            }
        if (empty($_POST["category_id"])) {
            $errors['category_id'] = "a category is required";
        }
        if (empty($_POST["artist_id"])) {
            $errors['artist_id'] = "a artist is required";
        }
        //image
        if (!empty($_FILES['image']['name'])) {

            $folder = "uploads/";
            if (!file_exists($folder)) {
                mkdir($folder, 077, true);
                file_put_contents($folder . "index.php", "");
            }
            $allowed = ['images/jpeg', 'images/png'];
            //&& in_array($_FILES['image']['type'], $allowed)
            if ($_FILES['image']['error'] == 0) {
                echo "bonjour";

                $destination_image = $folder . $_FILES['image']['name'];

                move_uploaded_file($_FILES['image']['tmp_name'], $destination_image);

            } else {
                $errors['image'] = "Image no valid. Allowed types are " . implode(",", $allowed);
            }

        } else {
            $errors['image'] = 'an image is required';
        }

        //file
        if (!empty($_FILES['file']['name'])) {

            $folder = "uploads/";
            if (!file_exists($folder)) {
                mkdir($folder, 077, true);
                file_put_contents($folder . "index.php", "");

            }
            $allowed = ['audio/mpeg'];

            if ($_FILES['file']['error'] == 0 && in_array($_FILES['file']['type'], $allowed)) {

                $destination_file = $folder . $_FILES['file']['name'];

                //move_uploaded_file($_FILES['file']['tmp_name'], $destination_file);
                if (move_uploaded_file($_FILES['file']['tmp_name'], $destination_file)) {
                    // Obtenir la durée du fichier
                    $duration = getAudioDuration($destination_file);

                    // Assurez-vous que $duration n'est pas false avant de continuer
                    if ($duration !== false) {
                        $values = [];

                        // Utilisez $roundedDuration ou $duration selon votre choix d'arrondissement
                        $roundedDuration = formatDuration(round($duration));
                        $values['duration'] = $roundedDuration;
                    } else {
                        $errors['file'] = "Could not retrieve audio duration.";
                    }
                }
            } else {
                $errors['file'] = "file not valid. Allowed types are " . implode(",", $allowed);
            }
        } else {
            $errors['file'] = 'audio file is required';
        }

        if (empty($errors)) {
            $values['title'] = trim($_POST['title']);
            $values['category_id'] = trim($_POST['category_id']);
            $values['artist_id'] = trim($_POST['artist_id']);
            $values['image'] = $destination_image;
            $values['file'] = $destination_file;
            $values['user_id'] = user('id');
            $values['date'] = date("Y-m-d H:i:s");
            $values['slug'] = str_to_url($values['title']);

            $query = "insert into musics(title,image,file,user_id,category_id,artist_id,date,slug,duration) values(:title,:image,:file,:user_id,:category_id,:artist_id,:date,:slug,:duration)";
            db_query($query, $values);
            message("name created successfully");
            redirect('manager/musics');
        }
    }
} else
    if ($action == 'edit') {
        $query = "select * from musics where id = :id limit 1";
        $row = db_query_one($query, ['id' => $id]);
        $values = [];

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];

            //data validation
            if (empty($_POST["title"])) {
                $errors['title'] = "a title is required";
            } else
                if (!preg_match("/^[a-zA-Z0-9 \.\&\-]+$/", $_POST['title'])) {
                    $errors['title'] = "a title can only have letters and spaces";
                }

            if (empty($_POST["category_id"])) {
                $errors['category_id'] = "a category is required";
            }
            if (empty($_POST["artist_id"])) {
                $errors['artist_id'] = "a artist is required";
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

                    $destination_image = $folder . $_FILES['image']['name'];

                    move_uploaded_file($_FILES['image']['tmp_name'], $destination_image);
                    //delete old file
                    if (file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                } else {
                    $errors['name'] = "Image no valid. Allowed types are " . implode(",", $allowed);
                }
            }
            //audio file
            if (!empty($_FILES['file']['name'])) {

                $folder = "uploads/";
                if (!file_exists($folder)) {
                    mkdir($folder, 077, true);
                    file_put_contents($folder . "index.php", "");
                }
                $allowed = ['audio/mpeg'];
                if ($_FILES['file']['error'] == 0 && in_array($_FILES['file']['type'], $allowed)) {

                    $destination_file = $folder . $_FILES['file']['name'];

                    //move_uploaded_file($_FILES['file']['tmp_name'], $destination_file);
                    if (move_uploaded_file($_FILES['file']['tmp_name'], $destination_file)) {
                        // Obtenir la durée du fichier
                        $duration = getAudioDuration($destination_file);

                        // Assurez-vous que $duration n'est pas false avant de continuer
                        if ($duration !== false) {

                            // Utilisez $roundedDuration ou $duration selon votre choix d'arrondissement
                            $roundedDuration = formatDuration(round($duration));
                            $values['duration'] = $roundedDuration;
                        } else {
                            $errors['file'] = "Could not retrieve audio duration.";
                        }
                    }
                    /*//delete old file
                    if (file_exists($row['file'])) {
                        unlink($row['file']);
                    }*/
                } else {

                    $errors['file'] = "File no valid. Allowed types are " . implode(",", $allowed);
                }
            }
            if (empty($errors)) {
                $values['title'] = trim($_POST['title']);
                $values['category_id'] = trim($_POST['category_id']);
                $values['artist_id'] = trim($_POST['artist_id']);
                $values['user_id'] = user('id');
                $values['slug'] = str_to_url($values['title']);

                $values['id'] = $id;

                $query = "update musics set  title = :title, user_id= :user_id, category_id= :category_id,artist_id= :artist_id ,slug= :slug";

                if (!empty($destination_image)) {
                    $query .= ",image= :image ";
                    $values['image'] = $destination_image;
                }
                if (!empty($destination_file)) {
                    $query .= ",file= :file,duration= :duration ";
                    $values['file'] = $destination_file;
                }
                $query .= " where id = :id limit 1 ";
                db_query($query, $values);
                message("Music edited successfully");
                redirect('manager/musics');
            }
        }
    } else
        if ($action == 'delete') {
            $query = "select * from musics where id = :id limit 1";
            $row = db_query_one($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $errors = [];

                if (empty($errors)) {

                    $values = [];
                    $values['id'] = $id;

                    $query = "delete from musics where id= :id limit 1";
                    db_query($query, $values);
                    //delete image
                    if (file_exists($row['image'])) {
                        unlink($row['image']);
                    }
                    //delete file
                    if (file_exists($row['file'])) {
                        unlink($row['file']);
                    }
                    message("music deleted successfully");
                    redirect('manager/musics');
                }
            }
        } else
            if ($action == 'search') {
                $title = $_GET['find'] ?? null;
                if (!empty($title)) {

                    $title = "%$title%";
                    $query = "select * from musics where title like :title ";
                    $rows = db_query($query, ['title' => $title]);
                }
            }
?>



<?php require page('includes/manager-header') ?>

<section class="manager-content" style="min-height:200px;">

    <!-- ---------ADD ------------ -->

    <?php if ($action == 'add'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Add new Music</h3>

                <input class="form-control my-1" type="text" name="title" value="<?= set_value('title') ?>"
                    placeholder="Music title">
                <?php if (!empty($errors['title'])): ?>
                    <small class="text-danger"><?= $errors['title'] ?></small>
                <?php endif; ?>

                <?php
                $query = "select * from categories order by category asc";
                $categories = db_query($query);
                ?>
                <select name="category_id" id="category_id" class="form-select my-1">
                    <option value="">--Select Category--</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option <?= set_select('category_id', $cat['id']) ?> value="<?= $cat['id'] ?>"><?= $cat['category'] ?>
                            </option>
                        <?php endforeach ?>
                    <?php endif ?>

                </select>

                <?php if (!empty($errors['category_id'])): ?>
                    <small class="text-danger"><?= $errors['category_id'] ?></small>
                <?php endif; ?>

                <?php
                $query = "select * from artists order by name asc";
                $artists = db_query($query);
                ?>
                <select name="artist_id" id="artist_id" class="form-select my-1">
                    <option value="">--Select Artist--</option>
                    <?php if (!empty($artists)): ?>
                        <?php foreach ($artists as $art): ?>
                            <option <?= set_select('artist_id', $art['id']) ?> value="<?= $art['id'] ?>"><?= $art['name'] ?></option>
                        <?php endforeach ?>
                    <?php endif ?>

                </select>

                <?php if (!empty($errors['artist_id'])): ?>
                    <small class="text-danger"><?= $errors['artist_id'] ?></small>
                <?php endif; ?>

                <div class="form-control m-1">
                    <div>Image :</div>
                    <input type="file" class="form-control my-1" name="image">
                    <?php if (!empty($errors['image'])): ?>
                        <small class="text-danger"><?= $errors['image'] ?></small>
                    <?php endif; ?>
                </div>

                <div class="form-control m-1">
                    <div>Audio File :</div>
                    <input type="file" class="form-control my-1" name="file">
                    <?php if (!empty($errors['file'])): ?>
                        <small class="text-danger"><?= $errors['file'] ?></small>
                    <?php endif; ?>
                </div>


                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/manager/musics">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            </form>
        </div>

        <!-- ---------EDIT ------------ -->

    <?php elseif ($action == 'edit'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post" enctype="multipart/form-data">
                <h3>Edit Music</h3>
                <?php if (!empty($row)): ?>

                    <input class="form-control my-1" type="text" name="title" value="<?= set_value('title', $row['title']) ?>"
                        placeholder="Music title">
                    <?php if (!empty($errors['title'])): ?>
                        <small class="text-danger"><?= $errors['title'] ?></small>
                    <?php endif; ?>

                    <?php
                    $query = "select * from categories order by category asc";
                    $categories = db_query($query);
                    ?>
                    <select name="category_id" id="category_id" class="form-select my-1">
                        <option value="">--Select Category--</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option <?= set_select('category_id', $cat['id'], $row['category_id']) ?> value="<?= $cat['id'] ?>">
                                    <?= $cat['category'] ?>
                                </option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>
                    <?php if (!empty($errors['category_id'])): ?>
                        <small class="text-danger"><?= $errors['category_id'] ?></small>
                    <?php endif; ?>

                    <?php
                    $query = "select * from artists order by name asc";
                    $artists = db_query($query);
                    ?>
                    <select name="artist_id" id="artist_id" class="form-select my-1">
                        <option value="">--Select Artist--</option>
                        <?php if (!empty($artists)): ?>
                            <?php foreach ($artists as $art): ?>
                                <option <?= set_select('artist_id', $art['id'], $row['artist_id']) ?> value="<?= $art['id'] ?>">
                                    <?= $art['name'] ?>
                                </option>
                            <?php endforeach ?>
                        <?php endif ?>
                    </select>
                    <?php if (!empty($errors['artist_id'])): ?>
                        <small class="text-danger"><?= $errors['artist_id'] ?></small>
                    <?php endif; ?>

                    <div class="form-control m-1">
                        <div>Image :</div>
                        <img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="" style="width:200px; height:200px; object-fit:cover;">

                        <input type="file" class="form-control my-1" name="image">
                        <?php if (!empty($errors['image'])): ?>
                            <small class="text-danger"><?= $errors['image'] ?></small>
                        <?php endif; ?>
                    </div>

                    <div class="form-control m-1">
                        <div>Audio File :</div>
                        <div>
                            <audio controls>
                                <source src="<?= ROOT ?>/<?= $row['file'] ?>" type="audio/mpeg">
                            </audio>
                        </div>
                        <input type="file" class="form-control my-1" name="file">
                        <?php if (!empty($errors['file'])): ?>
                            <small class="text-danger"><?= $errors['file'] ?></small>
                        <?php endif; ?>
                    </div>

                    <button class="btn bg-orange">Save</button>
                    <a href="<?= ROOT ?>/manager/musics">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/manager/musics">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>

        <!-- ---------DELETE ------------ -->

    <?php elseif ($action == 'delete'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Delete Music</h3>
                <?php if (!empty($row)): ?>

                    <div class="form-control my-1">
                        <?= set_value('title', $row['title']) ?>
                    </div>
                    <?php if (!empty($errors['title'])): ?>
                        <small class="text-danger"><?= $errors['title'] ?></small>
                    <?php endif; ?>

                    <button class="btn bg-danger">Delete</button>
                    <a href="<?= ROOT ?>/manager/musics">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/manager/musics">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>

    <?php else: ?>
        <div class="search">
            <form action="<?= ROOT ?>/manager/musics" method="get">
                <div class="form-group">
                    <input class="form-control" type="text" placeholder="Search for music" name="find">
                    <button class="btn">Search</button>
                </div>
            </form>
        </div>

        <?php
        $query = "select * from musics";
        $params = [];

        $title = $_GET['find'] ?? null;
        if (!empty($title)) {
            $query .= " WHERE title LIKE :title";
            $params['title'] = "%$title%";
        }

        $rows = db_query($query, $params);
        ?>
        <h3>Musics
            <a href="<?= ROOT ?>/manager/musics/add">
                <button class=" float-end btn bg-purpule">Add new</button>
            </a>
        </h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Image</th>
                <th>Category</th>
                <th>Artist</th>
                <th>Duration</th>
                <th>Audio</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></th>
                        <td><?= $row['title'] ?></td>
                        <td><img src="<?= ROOT ?>/<?= $row['image'] ?>" alt="" style="width:100px; height:100px; object-fit:cover;">
                        </td>
                        <td><?= get_category($row['category_id']) ?></td>
                        <td><?= get_artist($row['artist_id']) ?></td>
                        <td><?= $row['duration'] ?></td>

                        <td>
                            <audio controls>
                                <source src="<?= ROOT ?>/<?= $row['file'] ?>" type="audio/mpeg">
                            </audio>
                        <td>
                            <a href="<?= ROOT ?>/manager/musics/edit/<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                </svg>
                            </a>
                            <a href="<?= ROOT ?>/manager/musics/delete /<?= $row['id'] ?>">
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
                    <td colspan="8">No music found</td>
                </tr>
            <?php endif; ?>
        </table>
    <?php endif; ?>
</section>
<?php require page('includes/manager-footer'); ?>