<?php
 /*-- ---------ADD ------------ --*/

if ($action == 'add') {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $errors = [];

        //data validation
        if (empty($_POST["username"])) {
            $errors['username'] = "a username is required";
        } else {
            if (!preg_match("/^[a-zA-Z]+$/", $_POST['username'])) {
                $errors['username'] = "a username can only have letters with no spaces";

            }
        }
        if (empty($_POST["email"])) {
            $errors['email'] = "a email is required";
        } else {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "email is not valid";

            } else {
                // Vérifier si l'email existe déjà dans la base de données
                $query = "select * from users where email = :email";
                $existingEmail = db_query_one($query, ['email' => trim($_POST['email'])]);
                if ($existingEmail) {
                    $errors['email'] = "This email is already registered";
                }
            }
        }
        if (empty($_POST["password"])) {
            $errors['password'] = "a password is required";
        } else {
            if ($_POST['password'] != $_POST['retype_password']) {
                $errors['password'] = "password do not match";

            } else {
                if (strlen($_POST['password']) < 8) {
                    $errors['password'] = "password must be 8 character or more";

                }
            }
        }
        if (empty($_POST["role"])) {
            $errors['role'] = "a role is required";
        }

        if (empty($errors)) {
            $values = [];
            $values['username'] = trim($_POST['username']);
            $values['email'] = trim($_POST['email']);
            $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $values['role'] = trim($_POST['role']);
            $values['date'] = date("Y-m-d H:i:s");

            $query = "insert into users(username,email,password,role,date) values(:username,:email,:password,:role,:date)";
            db_query($query, $values);
            message("user created successfully");
            redirect('admin/users');

        }
    }
} else
 /*-- ---------EDIT ------------ --*/

    if ($action == 'edit') {
        $query = "select * from users where id = :id limit 1";
        $row = db_query_one($query, ['id' => $id]);

        if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
            $errors = [];
            if ($row['id'] == 1) {
                if ($_POST['role'] == "manager")
                    $errors['role'] = "The main admin role can not be edited";
            }
            //data validation
            if (empty($_POST["username"])) {
                $errors['username'] = "a username is required";
            } else {
                if (!preg_match("/^[a-zA-Z]+$/", $_POST['username'])) {
                    $errors['username'] = "a username can only have letters with no spaces";

                }
            }
            if (empty($_POST["email"])) {
                $errors['email'] = "a email is required";
            } else {
                if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors['email'] = "email is not valid";

                } else if ($row['email'] != $_POST['email']) {
                    // Vérifier si l'email existe déjà dans la base de données
                    $query = "select * from users where email = :email";
                    $existingEmail = db_query_one($query, ['email' => trim($_POST['email'])]);
                    if ($existingEmail) {
                        $errors['email'] = "This email is already registered";
                    }
                }
            }
            if (!empty($_POST["password"])) {
                if ($_POST['password'] != $_POST['retype_password']) {
                    $errors['password'] = "password do not match";

                } else {
                    if (strlen($_POST['password']) < 8) {
                        $errors['password'] = "password must be 8 character or more";
                    }
                }
            }
            if (empty($_POST["role"])) {

                $errors['role'] = "a role is required";
            }
            if (empty($errors)) {
                $values = [];
                $values['username'] = trim($_POST['username']);
                $values['email'] = trim($_POST['email']);
                $values['role'] = trim($_POST['role']);
                $values['id'] = $id;


                $query = "update users set email = :email, username = :username, role= :role where id= :id limit 1";

                if (!empty($_POST['password'])) {
                    $query = "update users set email = :email, password= :password, username = :username, role= :role where id= :id limit 1";
                    $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
                }

                db_query($query, $values);
                message("user edited successfully");
                redirect('admin/users');
            }
        }
    } else
     /*-- ---------DELETE ------------ --*/

        if ($action == 'delete') {
            $query = "select * from users where id = :id limit 1";
            $row = db_query_one($query, ['id' => $id]);

            if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
                $errors = [];

                if ($row['id'] == 1) {
                    $errors['username'] = "The main admin can not be deleted";
                }

                if (empty($errors)) {
                    $values = [];
                    $values['id'] = $id;

                    $query = "delete from users where id= :id limit 1";

                    db_query($query, $values);
                    message("user deleted successfully");
                    redirect('admin/users');
                }
            }
        }
?>


<?php require page('includes/admin-header') ?>
    <!-- ---------ADD ------------ -->

<section class="admin-content" style="min-height:80vh;">
    <?php if ($action == 'add'): ?>

        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Add new User</h3>

                <input class="form-control my-1" type="text" name="username" value="<?= set_value('username') ?>"
                    placeholder="Username">
                <?php if (!empty($errors['username'])): ?>
                    <small class="text-danger"><?= $errors['username'] ?></small>
                <?php endif; ?>
                <input class="form-control my-1" type="email" name="email" value="<?= set_value('email') ?>"
                    placeholder="Email">
                <?php if (!empty($errors['email'])): ?>
                    <small class="text-danger"><?= $errors['email'] ?></small>
                <?php endif; ?>
                <select name="role" id="role" class="form-select my-1">
                    <option value="">--Select Role--</option>
                    <option <?= set_select('role', 'admin') ?> value="admin">Admin</option>
                    <option <?= set_select('role', 'manager') ?> value="manager">Manager</option>

                </select>
                <?php if (!empty($errors['role'])): ?>
                    <small class="text-danger"><?= $errors['role'] ?></small>
                <?php endif; ?>
                <input class="form-control my-1" type="password" name="password" value="<?= set_value('password') ?>"
                    placeholder="Password">
                <?php if (!empty($errors['password'])): ?>
                    <small class="text-danger"><?= $errors['password'] ?></small>
                <?php endif; ?>
                <input class="form-control my-1" type="password" name="retype_password"
                    value="<?= set_value('retype_password') ?>" placeholder="Retype Password">

                <button class="btn bg-orange">Save</button>
                <a href="<?= ROOT ?>/admin">
                    <button type="button" class="float-end btn">Back</button>
                </a>
            </form>
        </div>
        <!-- ---------EDIT ------------ -->

    <?php elseif ($action == 'edit'): ?>
        <div class="" style="max-width:500px; margin:auto;">
            <form action="" method="post">
                <h3>Edit User</h3>

                <?php if (!empty($row)): ?>

                    <input class="form-control my-1" type="text" name="username"
                        value="<?= set_value('username', $row['username']) ?>" placeholder="Username">
                    <?php if (!empty($errors['username'])): ?>
                        <small class="text-danger"><?= $errors['username'] ?></small>
                    <?php endif; ?>
                    <input class="form-control my-1" type="email" name="email" value="<?= set_value('email', $row['email']) ?>"
                        placeholder="Email">
                    <?php if (!empty($errors['email'])): ?>
                        <small class="text-danger"><?= $errors['email'] ?></small>
                    <?php endif; ?>
                    <select name="role" id="role" class="form-select my-1">
                        <option value="">--Select Role--</option>
                        <option <?= set_select('role', 'admin', $row['role']) ?> value="admin">Admin</option>
                        <option <?= set_select('role', 'manager', $row['role']) ?> value="manager">Manager</option>

                    </select>

                    <?php if (!empty($errors['role'])): ?>
                        <small class="text-danger"><?= $errors['role'] ?></small>
                    <?php endif; ?>
                    <input class="form-control my-1" type="password" name="password"
                        placeholder="Password (leave empty to keep old one)" value="<?= set_value('password') ?>"
                        placeholder="Password">
                    <?php if (!empty($errors['password'])): ?>
                        <small class="text-danger"><?= $errors['password'] ?></small>
                    <?php endif; ?>
                    <input class="form-control my-1" type="password" name="retype_password"
                        value="<?= set_value('retype_password') ?>" placeholder="Retype Password">

                    <button class="btn bg-orange">Save</button>
                    <a href="<?= ROOT ?>/admin">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/admin">
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
                        <?= set_value('username', $row['username']) ?>
                    </div>
                    <?php if (!empty($errors['username'])): ?>
                        <small class="text-danger"><?= $errors['username'] ?></small>
                    <?php endif; ?>
                    <div class="form-control my-1">
                        <?= set_value('email', $row['email']) ?>
                    </div>
                    <div class="form-control my-1">
                        <?= set_value('role', $row['role']) ?>
                    </div>

                    <button class="btn bg-danger">Delete</button>
                    <a href="<?= ROOT ?>/admin">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php else: ?>
                    <div class="alert">That record was not found</div>
                    <a href="<?= ROOT ?>/admin">
                        <button type="button" class="float-end btn">Back</button>
                    </a>
                <?php endif; ?>
            </form>
        </div>
    <?php else: ?>
        <?php
        $query = "select * from users where role != 'user' order by id desc ";
        $rows = db_query($query);
        ?>
        <h3>Users
            <a href="<?= ROOT ?>/admin/users/add">

                <button class=" float-end btn bg-purpule">Add new</button>
            </a>
        </h3>
        <table class="table text-center">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            <?php if (!empty($rows)): ?>
                <?php foreach ($rows as $row): ?>
                    <tr>
                        <td><?= $row['id'] ?></th>
                        <td><?= $row['username'] ?></td>
                        <td><?= $row['email'] ?></td>
                        <td><?= $row['role'] ?></td>
                        <td><?= get_date($row['date']) ?></td>
                        <td>
                            <a href="<?= ROOT ?>/admin/users/edit/<?= $row['id'] ?>">
                                <svg width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                    <path
                                        d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325" />
                                </svg>
                            </a>
                            <a href="<?= ROOT ?>/admin/users/delete /<?= $row['id'] ?>">
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