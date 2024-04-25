<?php 
if($_SERVER['SERVER_NAME'] == "localhost")
{
    //for local server

    define ("ROOT","http://localhost/music_for_you/public");
    //database connection
    define("DBHOST", "localhost");
    define("DBDRIVER", "mysql");
    define("DBUSER", "root");
    define("DBPASS","");
    define("DBNAME", "music_for_you");
}
else
{
    //for online server
    define ("ROOT","http://www.mywebsite.com");
     
    //database connection
    define("DBHOST","localhost");
    define("DBRIVER","mysql");
    define("DBUSER","root");
    define("DBPASS","");
    define("DBNAME","music_for_you");
}
