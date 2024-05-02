<?php
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

    if (empty($errors)) {

        $values = [];
        $values['username'] = trim($_POST['username']);
        $values['email'] = trim($_POST['email']);
        $values['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $values['role'] = 'user';
        $values['date'] = date("Y-m-d H:i:s");

        $query = "insert into users(username,email,password,role,date) values(:username,:email,:password,:role,:date)";
        db_query($query, $values);
        //message("user created successfully");
        // Clear the POST data to reset the form fields

        $value['email'] = trim($_POST['email']);

        $query1 = "select * from users where email = :email";
        $row = db_query_one($query1, $value);
        authenticate($row);
        redirect('home');
    }
}
?>

<?php require page('includes/header'); ?>

<section class="content">
    <div class="login-holder">
        <?php if (message()): ?>
            <div class="alert"><?= message('', true) ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <center>
                <img src="<?= ROOT ?>/assets/images/logo.jpg" alt=""
                    style="width: 150px; border-radius: 50%; border: solid thin #ccc">
            </center>
            <h2>Sign Up</h2>
            <input value="<?= set_value('username') ?>" class=" my-1 form-control " type="text" name="username"
                placeholder="Username">
            <?php if (!empty($errors['username'])): ?>
                <small class="text-danger"><?= $errors['username'] ?></small>
            <?php endif; ?>
            <input value="<?= set_value('email') ?>" class=" my-1 form-control " type="email" name="email"
                placeholder="Email">
            <?php if (!empty($errors['email'])): ?>
                <small class="text-danger"><?= $errors['email'] ?></small>
            <?php endif; ?>
            <input value="<?= set_value('password') ?>" class=" my-1 form-control" type="password" name="password"
                placeholder="Password">
            <?php if (!empty($errors['password'])): ?>
                <small class="text-danger"><?= $errors['password'] ?></small>
            <?php endif; ?>
            <input class="form-control my-1" type="password" name="retype_password"
                value="<?= set_value('retype_password') ?>" placeholder="Retype Password">
            <!-- Sign Up link -->
            <p class="">I have an account <a href="<?= ROOT ?>/login">Login</a></p>
            <button class=" my-1 btn bg-blue">Sign Up</button>
        </form>
    </div>
</section>
<?php require page('includes/footer'); ?>