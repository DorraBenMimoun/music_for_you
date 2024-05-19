<?php
require_once 'includes/header.php';

// Vérifier s'il y a des erreurs dans la session
$errors = isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
// Supprimer les erreurs de la session pour éviter de les afficher à nouveau
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <section class="content">
    <div class="login-holder">
        
        <form action="../app/controller/userController.php"  method="post">
            <center>
                <img src="<?= ROOT ?>/assets/images/logo.jpg" alt="" style="width: 150px; border-radius: 50%; border: solid thin #ccc">
            </center>
            <h2>Sign Up</h2>
            <input type="hidden" name="page" value="signup">

            <input class=" my-1 form-control " type="text" name="username" placeholder="Username">
            <?php if (!empty($errors['username'])): ?>
                <small class="text-danger"><?= $errors['username'] ?></small>
            <?php endif; ?>
            <input class=" my-1 form-control " type="email" name="email" placeholder="Email">
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
            <button class=" my-1 btn bg-blue" type="submit" >Sign Up2</button>
        </form>
    </div>
</section>
  

</body>

</html>