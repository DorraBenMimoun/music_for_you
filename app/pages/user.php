<?php

if (!is_user()) {
    message("Only users can access the user page");
    redirect('login');
}


$section = $URL[1];
$action = $URL[2] ?? null;
$id_playlist = $URL[3] ?? null;
$id_music = $URL[4] ?? null;

switch ($section) {
    case 'playlists':
        require page('user/playlists');
        break;
    case 'playlist_songs':
        require page('user/playlist_songs');
        break;



    default:
        require page('/404');

        break;
}






