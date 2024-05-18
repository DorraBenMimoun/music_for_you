<?php require_once 'includes/header.php'; ?>
<section class="content">
    <div class="login-holder">
        <?php if (message()): ?>
            <div class="alert"><?= message('', true) ?></div>
        <?php endif; ?>
        <form id="signupForm" action="" method="post">
            <center>
                <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="" style="width: 150px; border-radius: 50%; border: solid thin #ccc">
            </center>
            <h2>Sign Up</h2>
            <input value="<?= isset($username) ? $username : '' ?>" class=" my-1 form-control " type="text" name="username" placeholder="Username">
            <?php if (!empty($errors['username'])): ?>
                <small class="text-danger"><?= $errors['username'] ?></small>
            <?php endif; ?>
            <input value="<?= isset($email) ? $email : '' ?>" class=" my-1 form-control " type="email" name="email" placeholder="Email">
            <?php if (!empty($errors['email'])): ?>
                <small class="text-danger"><?= $errors['email'] ?></small>
            <?php endif; ?>
            <input class=" my-1 form-control" type="password" name="password" placeholder="Password">
            <?php if (!empty($errors['password'])): ?>
                <small class="text-danger"><?= $errors['password'] ?></small>
            <?php endif; ?>
            <input class="form-control my-1" type="password" name="retype_password" placeholder="Retype Password">
            <!-- Sign Up link -->
            <p class="">I have an account <a href="<?= ROOT ?>/login">Login</a></p>
            <button class=" my-1 btn bg-blue" onclick="signup()" >Sign Up2</button>
        </form>
    </div>
</section>
<script>
function signup() {
    var formData = new FormData(document.getElementById('signupForm'));
    fetch('/controller/userController.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Gérer la réponse de la fonction signup ici
        console.log(data);
    })
    .catch(error => {
        // Gérer les erreurs ici
        console.error(error);
    });
}
</script>
<?php require_once 'includes/footer.php'; ?>
