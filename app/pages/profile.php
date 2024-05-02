<?php ob_start();
require page('includes/header'); ?>
<?php
$id = $URL[1] ?? null;

$query = "select * from users where id = :id limit 1";
$row = db_query_one($query, ['id' => $id]);

if ($_SERVER['REQUEST_METHOD'] == "POST" && $row) {
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

    if (empty($errors)) {
        $values = [];
        $values['username'] = trim($_POST['username']);
        $values['email'] = trim($_POST['email']);
        $values['id'] = $id;

        $query = "update users set email = :email, username = :username where id= :id limit 1";

        if (!empty($_POST['password'])) {
            $query = "update users set email = :email, password= :password, username = :username where id= :id limit 1";
            $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        db_query($query, $values);
        //$_SESSION['USER']['name']= $values['username'];
        //echo $_SESSION['USER']['name'];
        //echo user('username');
        $success = "Profile edited successfully.";
        //redirect('home');

    }
}
?>
<div class="" style="max-width:500px; margin:auto;">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= $error ?></p>
            <?php endforeach; ?>
        </div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success">
            <?= $success ?>
        </div>
    <?php endif; ?>
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

            <input class="form-control my-1" type="password" name="password"
                placeholder="Password (leave empty to keep old one)" value="<?= set_value('password') ?>"
                placeholder="Password">
            <?php if (!empty($errors['password'])): ?>
                <small class="text-danger"><?= $errors['password'] ?></small>
            <?php endif; ?>
            <input class="form-control my-1" type="password" name="retype_password"
                value="<?= set_value('retype_password') ?>" placeholder="Retype Password">

            <button class="btn bg-orange">Save</button>
            <a href="<?= ROOT ?>/musics">
                <button type="button" class="float-end btn">Back</button>
            </a>
        <?php else: ?>
            <div class="alert">That record was not found</div>
            <a href="<?= ROOT ?>/musics">
                <button type="button" class="float-end btn">Back</button>
            </a>
        <?php endif; ?>
    </form>
</div>
<?php require page('includes/footer'); ?>