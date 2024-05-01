
<?php

if (!is_manager()) {
    message("Only admins can access the manager page");
    redirect('login');
}


$section = $URL[1] ?? "dashboard";
$action = $URL[2] ?? null;
$id = $URL[3] ?? null;
switch ($section) {
    case 'dashboard':
        require page('manager/musics');
        break;
    
    case 'categories':
        require page('manager/categories');
        break;
    case 'artists':
        require page('manager/artists');
        break;
    case 'musics':
        require page('manager/musics');
        break;
    case 'artist':
        require page('manager/artist');
        break;

    default:
        require page('manager/404');

        break;
}






