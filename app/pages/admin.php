
<?php
ob_start();

if (!is_admin()) {
    message("Only admins can access the admin page");
    redirect('login');
}


$section = $URL[1] ?? "dashboard";
$action = $URL[2] ?? null;
$id = $URL[3] ?? null;
switch ($section) {
    case 'dashboard':
        require page('admin/users');
        break;
    case 'users':
        require page('admin/users');
        break;

    default:
        require page('admin/404');

        break;
}






