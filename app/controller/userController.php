<?php
session_start();

require_once '../model/userModel.php';

/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "hello";
    // Récupération des données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

 
        // Création d'un nouvel utilisateur
        $utilisateur = new User($username,$email,$password);
        $utilisateur->save();

        echo "Inscription réussie !";
    
}*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    if ($_POST['page'] == "signup") {
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


            // Récupération des données du formulaire
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];


            $utilisateur = new User($username, $email, $password);
            $utilisateur->signup();


        } else {
            $_SESSION['errors'] = $errors;
            header("Location: /music_for_you/public/signup3");
            exit; // Assurez-vous de terminer l'exécution du script après la redirection

        }
    }

    if ($_POST['page'] == "login") {

        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $utilisateur = new User('', $email, $password);
        $utilisateur->login();
    }


}