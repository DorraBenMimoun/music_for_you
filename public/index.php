<?php
session_start();
require "../app/core/init.php";

 
if($_GET['url'] == "")
$URL= "home";
else 
$URL=$_GET['url'] ;


$URL = explode("/", $URL);

/**page is a function that exist in app/core/function.php */
$file=page(strtolower($URL[0]));

if(file_exists($file)){
    require $file;
}
else
{
    require page("404");
}


