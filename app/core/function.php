<?php

function show($stuff)
{
    echo "<pres";
    print_r($stuff);
    echo "</pre>";
}

function page($file)
{
    return "../app/pages/" . $file . ".php";
}

function db_connect()
{
    $string = "mysql:hostname=localhost;dbname=music_for_you";
    $con = new PDO($string, "root", "");
    return $con;
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

