

<?php require page('includes/header'); ?>

<section class="content">
    <div class="login-holder">
        <?php if (message()): ?>
            <div class="alert"><?= message('', true) ?></div>
        <?php endif; ?>
        <form action="../app/controller/userController.php" method="post">
            <center>
                <img src="<?= ROOT ?>/assets/images/logo.jpg" alt=""
                    style="width: 150px; border-radius: 50%; border: solid thin #ccc">
            </center>
            <h2>Login</h2>
    
            <input type="hidden" name="page" value="login">
            <input class=" my-1 form-control " type="email" name="email"
                placeholder="Email">
            <input class=" my-1 form-control" type="password" name="password"
                placeholder="Password">
            <!-- Sign Up link -->
            <p class="text-center">Don't have an account? <a href="<?= ROOT ?>/signup3">Sign Up</a></p>
            <button class=" my-1 btn bg-blue" type="submit">Login</button>
        </form>
    </div>
</section>
<?php require page('includes/footer'); ?>