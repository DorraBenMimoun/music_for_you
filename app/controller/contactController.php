<?php
session_start();

require_once '../model/contactModel.php';
var_dump($_POST); // Pour vérifier que les données sont bien reçues

if (isset($_POST["name"]) && isset($_POST["email"]) && isset($_POST["message"]) && isset($_POST["number"])) {
    // Récupération des données du formulaire
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $number = $_POST['number'];

    //data validation
    $errors = [];
    if (empty($name)) {
        $errors['name'] = "A name is required";
    } else {
        if (!preg_match("/^[a-zA-Z]+$/", $name)) {
            $errors['name'] = "A name can only have letters with no spaces";
        }
    }
    if (empty($email)) {
        $errors['email'] = "An email is required";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email is not valid";
        }
    }
    if (empty($message)) {
        $errors['message'] = "A message is required";
    }
    if (empty($number)) {
        $errors['number'] = "A number is required";
    }

    if (empty($errors)) {
        $contact = new Contact($name, $email, $message, $number);
        $contact->sendMessage();
    } else {
        $_SESSION['errors'] = $errors;
        header("Location: /music_for_you/public/contact");
        exit; // Assurez-vous de terminer l'exécution du script après la redirection
    }
}


/*if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    echo "allo".$_POST["name"];
    echo $_POST["email"];
        //data validation
        if (empty($_POST["name"])) {
            $errors['name'] = "a name is required";
        } else {
            if (!preg_match("/^[a-zA-Z]+$/", $_POST['name'])) {
                $errors['name'] = "a name can only have letters with no spaces";

            }
        }
        if (empty($_POST["email"])) {
            $errors['email'] = "a email is required";
        } else {
            if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "email is not valid";

            } 
        }
        if (empty($_POST["message"])) {
            $errors['message'] = "a message is required";
        } 
        if (empty($_POST["number"])) {
            $errors['number'] = "a number is required";
        } 

        if (empty($errors)) {


            // Récupération des données du formulaire
            $name = $_POST['name'];
            $email = $_POST['email'];
            $message = $_POST['message'];
            $number = $_POST['number'];



            $contact = new Contact($name, $email, $message, $number);
            $contact->sendMessage();


        } else {
            $_SESSION['errors'] = $errors;
            header("Location: /music_for_you/public/contact");
            exit; // Assurez-vous de terminer l'exécution du script après la redirection

        }


}
else
{echo "error";}*/