<?php
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = [];

    $values = [];
    $values['email'] = trim($_POST['email']);
    $query = "select * from users where email = :email";
    $row = db_query_one($query, $values);

    if (!empty($row)) {

        if (password_verify($_POST['password'], $row['password'])) {
            authenticate($row);
            if ($row['role'] == "admin")
                redirect('admin');
            else
                if ($row['role'] == "manager")
                    redirect('manager');
                else
                    redirect('home');
        }
    }
    message("Wrong email or password");
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
            <h2>Login</h2>
            <input value="<?= set_value('email') ?>" class=" my-1 form-control " type="email" name="email"
                placeholder="Email">
            <input value="<?= set_value('password') ?>" class=" my-1 form-control" type="password" name="password"
                placeholder="Password">
            <!-- Sign Up link -->
            <p class="text-center">Don't have an account? <a href="<?= ROOT ?>/signup">Sign Up</a></p>
            <button class=" my-1 btn bg-blue">Login</button>
        </form>
    </div>
</section>
<?php require page('includes/footer'); ?>