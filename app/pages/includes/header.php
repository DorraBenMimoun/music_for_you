<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> <?=ucfirst($URL[0])?> - Music For You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=ROOT?>/assets/css/style.css">
</head>
<body>

    <header>
        <!-- lOGO GAUCHE -->
        <div class="logo-holder">
            <a href="<?=ROOT?>/"><img src="<?=ROOT?>/assets/images/logo.png" alt=""></a></a>
        </div>

        <!-- RECHERCHE -->
        <div class="main-title">
                <div class="recherche_bar">
                    <form action="<?=ROOT?>/search" >
                        <div class="form-group">
                            <input class="form-control" type="text" placeholder="Search for music" name="find">
                            <button class="btnSearch"><i class='bx bx-search'></i></button>
                        </div>
                    </form>
                </div>
        </div> 
        <div class="header-div">
           
            <!-- LISTE DES LIENS -->
            <div class="list">
                <div class="nav-item"><a href="<?=ROOT?>/">Home</a></div>
                <div class="nav-item"><a href="<?=ROOT?>/musics">Musics</a></div>
              
                <div class="nav-item"><a href="<?=ROOT?>/artists">Artists</a></div>
                <div class="nav-item"><a href="<?=ROOT?>/playlists">Playlists</a></div>
                <?php if(!is_admin() && !is_manager()):?>

                <div class="nav-item"><a href="<?=ROOT?>/about">About us</a></div>
                <div class="nav-item"><a href="<?=ROOT?>/contact">Contact us</a></div>
                <?php endif;?>

                <?php if(logged_in()):?>
                <div class="nav-item dropdown dropdown-toggle">
                    <a href="#">Hi,<?=user('username')?></a>
                    <div class="dropdown-list hide">
                        <?php if(is_user()):?>
                            <div class="nav-item"><a href="<?=ROOT?>/user/playlists">My Playlist</a></div>

                        <div class="nav-item"><a href="<?=ROOT?>/profile/<?=user('id')?>">Profile</a></div>
                        <?php endif;?>
                        <?php if(is_admin()):?>
                            <div class="nav-item"><a href="<?=ROOT?>/admin">Admin</a></div>
                        <?php endif;?>
                        <?php if(is_manager()):?>
                            <div class="nav-item"><a href="<?=ROOT?>/manager">Manager</a></div>
                        <?php endif;?>


                        <div class="nav-item"><a href="<?=ROOT?>/logout">Logout</a></div>

                    </div>
                </div>
                <?php else: ?>
                    <div class="nav-item loginBtn"  ><a href="<?=ROOT?>/login2">Login</a></div>

                <?php endif;?>


            </div>
        </div>
    </header>
