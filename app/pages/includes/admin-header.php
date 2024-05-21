
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=ucfirst($URL[0])?> -Music For You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/style.css?123">
</head>

<body>
    <style>
        header a{
            color:black;
        }
   
    </style>

<header>
        <!-- lOGO GAUCHE -->
        <div class="logo-holder">
            <a href="<?=ROOT?>/"><img src="<?=ROOT?>/assets/images/logo.png" alt=""></a></a>
        </div>
        <div class="header-div">
            <div class="main-title">
                ADMIN AREA
            </div>
            <div class="main-nav float-end m-5">
  
                <div class="nav-item dropdown dropdown-toggle">
                    <a href="#">Hi,<?=user('username')?></a>
                    <div class="dropdown-list hide">
                                              <div class="nav-item"><a href="<?=ROOT?>/">Website</a></div>

                       

                        <div class="nav-item"><a href="<?=ROOT?>/logout">Logout</a></div>

                    </div>
                </div>


            </div>
        </div>
    </header>

<?php if(message()):?>
    <div class="alert" ><?=message('',true)?></div>
<?php endif;?>