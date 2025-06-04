<?php

//host
define("HOST","localhost");

//user
define("USER","root");

//dbname
define("DBNAME","hotelbooking");

//password
define("PASS","");


$conn = mysqli_connect(HOST,USER,PASS,DBNAME);
if(!$conn){
    die("Connection failed: ".mysqli_connect_error());
}

?>