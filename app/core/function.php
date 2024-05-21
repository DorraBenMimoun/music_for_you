<?php

function show($stuff)
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

// Returns the path to a specific page file in the 'pages' directory.
function page($file)
{
    return "../app/pages/" . $file . ".php";
}

function db_connect()
{
    // Connect to database
    /*$string = "mysql:hostname=localhost;dbname=music_for_you";
    $con = new PDO($string, "root", "");
    return $con;*/

    try {
        // Connect to database
        $string = "mysql:host=localhost;dbname=music_for_you";
        $con = new PDO($string, "root", "");
        // Set PDO to throw exceptions
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $con;
    } catch (PDOException $e) {
        // Handle connection error
        echo "Connection failed: " . $e->getMessage();
        exit; // Stop script execution
    }
}

function db_query($query, $data = array())
{   
    $con = db_connect();
    $stm = $con->prepare($query);
    if ($stm) {
        $check = $stm->execute($data);
        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result)>0) {
                return $result;
            }
        }
    }

    return false;
}
function db_query_one($query, $data = array())
{
    $con = db_connect();
    $stm = $con->prepare($query);
    if ($stm) {
        $check = $stm->execute($data);
        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($result) && count($result) > 0) {
                return $result[0];
            }
        }
    }

    return false;
}

function message($message = '', $clear = false)
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        if (!empty($_SESSION['message'])) {
            $msg = $_SESSION['message'];
            if ($clear) {
                unset($_SESSION['message']);
            }
            return $msg;
        }
    }
    return false;
}

function redirect($page)
{
    header("Location: " . ROOT . "/" . $page);
    die;
}

function set_value($key,$default='')
{
    if (!empty($_POST[$key])) {
        return $_POST[$key];
    }
    else
    {
        return $default;
    }

}
function set_select($key, $value,$default='')
{
    if (!empty($_POST[$key])) {
        if ($_POST[$key] == $value) {
            return "selected";
        }
    }
    else
    {
        if ($default == $value) {
            return "selected";
        } 
    }
    return '';
}

function get_date($date)
{
    return date("jS M, Y",strtotime($date));
}

function logged_in()
{
    
    if(!empty($_SESSION['USER']) && is_array($_SESSION['USER']))
    {
        return true;
    }

    return false;
}
function is_admin()
{

    if(!empty($_SESSION['USER']['role']) && $_SESSION['USER']['role']=='admin')

    {
        return true;
    }
    return false;
}
function is_manager()
{

    if(!empty($_SESSION['USER']['role']) && $_SESSION['USER']['role']=='manager')

    {
        return true;
    }
    return false;
}
function is_user()
{

    if(!empty($_SESSION['USER']['role']) && $_SESSION['USER']['role']=='user')

    {
        return true;
    }
    return false;
}
function user($column)
{
    if(!empty($_SESSION['USER'][$column]) )

    {
        return $_SESSION['USER'][$column];
    }
    return "Unknown";
}
function authenticate($row)
{

    $_SESSION['USER'] = $row;


}

function str_to_url($url)
{
    //supprime toutes les apostrophes de la chaîne
    $url = str_replace("'","", $url);
    //Remplaceme les caractères non-alphanumériques
    $url = preg_replace('~[^\\pL0-9_]+~u','-', $url);
    //supprime les tirets du début et de la fin de la chaîne
    $url = trim($url,"-");
    // translittérer les caractères non-ASCII en ASCI
    $url = iconv("utf-8","us-ascii//TRANSLIT", $url);
    $url = strtolower($url);
    $url = preg_replace('~[^-a-z0-9_]+~','', $url);

    return $url;
}

function get_category($id)
{
    $query = " select name from categories where id = :id limit 1" ;
    $row = db_query_one($query,['id'=>$id]);
    if (!empty($row['name']))
    {
        return $row['name'];
    }
    return "Unknown";
}
function get_user_name($id)
{
    $query = " select username from users where id = :id limit 1" ;
    $row = db_query_one($query,['id'=>$id]);
    if (!empty($row['username']))
    {
        return $row['username'];
    }
    return "Unknown";
}
function get_artist($id)
{
    $query = " select name from artists where id = :id limit 1" ;
    $row = db_query_one($query,['id'=>$id]);
    if (!empty($row['name']))
    {
        return $row['name'];
    }
    return "Unknown";
}
function get_music($id)
{
    $query = " select * from musics where id = :id limit 1" ;
    $rows = db_query_one($query,['id'=>$id]);
    if (!empty($rows))
    {

        return $rows;
    }
    return "Unknown";
}
function get_playlist($id)
{
    $query = " select * from playlists where id = :id limit 1" ;
    $rows = db_query_one($query,['id'=>$id]);
    if (!empty($rows))
    {

        return $rows;
    }
    return "Unknown";
}
function esc($str)
{
    return nl2br(htmlspecialchars($str));
}

// require_once 'C:\xampp\htdocs\music_for_you\vendor\autoload.php';
function getAudioDuration($file) {
    $getID3 = new getID3;
    $fileInfo = $getID3->analyze($file);
    if (isset($fileInfo['playtime_seconds'])) {
        return $fileInfo['playtime_seconds'];
    }
    return false;
}
function formatDuration($seconds) {
    $minutes = floor($seconds / 60);
    $remainingSeconds = $seconds % 60;
    $decimal = $remainingSeconds / 60;  // Convertir les secondes en fraction de minute
    $totalMinutes = $minutes + $decimal;  // Additionner les minutes et la fraction décimale

    return round($totalMinutes, 2);  // Arrondir à deux chiffres décimaux
}
