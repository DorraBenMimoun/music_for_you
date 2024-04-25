<?php

if ($action == 'add') {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $errors = [];

        //data validation
        if (empty($_POST["category"])) {
            $errors['category'] = "a category is required";
        } else {
            if (!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['category'])) {
                $errors['category'] = "a category can only have letters and spaces";

            }
        }
        if (empty($errors)) {
            $values = [];
            $values['category'] = trim($_POST['category']);
            $values['disabled'] = trim($_POST['disabled']);

            $query = "insert into categories(category,disabled) values(:category,:disabled)";
            db_query($query, $values);
            message("category created successfully");
            redirect('admin/categories');

        }
    }
} else
    if ($action == 'edit') {
        $query = "select * from categories where id = :id limit 1";
        $row = db_query_one($query, ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];

            //data validation
            if (empty($_POST["category"])) {
                $errors['category'] = "a category is required";
            } else {
                if (!preg_match("/^[a-zA-Z \&\-]+$/", $_POST['category'])) {
                    $errors['category'] = "a category can only have letters with no spaces";

                }
            }



            if (empty($errors)) {
                $values = [];
                $values['category'] = trim($_POST['category']);
                $values['disabled'] = trim($_POST['disabled']);
                $values['id'] = $id;


                $query = "update categories set  category = :category, disabled= :disabled where id= :id limit 1";

                db_query($query, $values);
                message("category edited successfully");
                redirect('admin/categories');

            }
        }



    } else
        if ($action == 'delete') {
            $query = "select * from categories where id = :id limit 1";
            $row = db_query_one($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $errors = [];



                if (empty($errors)) {
                    $values = [];
                    $values['id'] = $id;


                    $query = "delete from categories where id= :id limit 1";



                    db_query($query, $values);
                    message("category deleted successfully");
                    redirect('admin/categories');

                }
            }
        }



?>



<?php require page('includes/admin-header') ?>

<section class="admin-content" style="min-height:200px;">
    <?php if ($action == 'add'): ?>

        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Add new Category</h3>

                <input class="form-control my-1" type="text" name="category" value="<?= set_value('category') ?>"
                    placeholder="category name">
                <?php if (!empty($errors['category'])): ?>
                    <small class="text-danger"><?= $errors['category'] ?></small>
                <?php endif; ?>

                <select name="disabled" id="disabled" class="form-select my-1">
                    <option value="">--Select Disabled--</option>
                    <option <?= set_select('disabled', '1') ?> value="1">Yes</option>
                    <option <?= set_select('disabled', '0') ?> value="0">No</option>
                </select>
                <?php if (!empty($errors['disabled'])): ?>
                    <small class="text-danger"><?= $errors['disabled'] ?></small>
                <?php endif; ?>

                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/admin/categories">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            </form>
        </div>
    <?php elseif ($action == 'edit'): ?>



        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Edit Category</h3>

                <?php if (!empty($row)): ?>

                    <input class="form-control my-1" type="text" name="category"
                        value="<?= set_value('category', $row['category']) ?>" placeholder="category">
                    <?php if (!empty($errors['category'])): ?>
                        <small class="text-danger"><?= $errors['category'] ?></small>
                    <?php endif; ?>
                    <select name="disabled" id="disabled" class="form-select my-1">
                        <option value="">--Select Disabled--</option>
                        <option <?= set_select('disabled', '1', $row['disabled']) ?> value="1">Yes</option>
                        <option <?= set_select('disabled', '0', $row['disabled']) ?> value="0">No</option>
                    </select>



                    <button class="btn bg-orange">Save</button>
                    <a href="<?= ROOT ?>/admin/categories">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/admin/categories">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    <?php elseif ($action == 'delete'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Delete Category</h3>

                <?php if (!empty($row)): ?>

                    <div class="form-control my-1">
                        <?= set_value('category', $row['category']) ?>
                    </div>
                    <?php if (!empty($errors['category'])): ?>
                        <small class="text-danger"><?= $errors['category'] ?></small>
                    <?php endif; ?>

                    <button class="btn bg-danger">Delete</button>
                    <a href="<?= ROOT ?>/admin/categories">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/admin/categories">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    <?php else: ?>
        <?php
        $query = "select * from categories order by id desc ";
        $rows = db_query($query);
        ?>


        <h3>Categories
            <a href="<?= ROOT ?>/admin/categories/add">

                <button class=" float-end btn bg-purpule">Add new</button>
            </a>
        </h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <th>Category</th>
                <th>Active</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></th>
                        <td><?= $row['category'] ?></td>
                        <td><?= $row['disabled'] ? 'NO' : 'Yes' ?></td>

                        <td>
                            <a href="<?= ROOT ?>/admin/categories/edit/<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                </svg>
                            </a>
                            <a href="<?= ROOT ?>/admin/categories/delete /<?= $row['id'] ?>">
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