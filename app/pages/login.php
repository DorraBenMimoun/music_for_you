<?php 
    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $errors = [];

        $values =[];
        $values['email']= trim($_POST['email']);
        $query="select * from users where email = :email";
        $row = db_query_one($query,$values);
        echo $row['password'];
        echo $_POST['password'];
    
      
        if (!empty($row)) 
        {
            echo "hello22";
            
            if(password_verify($_POST['password'], $row['password']))
            {
                echo "hello";
                authenticate($row);
                message(" login successful");
                redirect('admin');
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
                <input value="<?=set_value('email')?>" class=" my-1 form-control " type="email" name="email" placeholder="Email">
                <input value="<?=set_value('password')?>" class=" my-1 form-control" type="password" name="password" placeholder="Password">
                <button class=" my-1btn bg-blue">Login</button>
            </form>

        </div>


    </section>
    <?php require page('includes/footer'); ?>

    