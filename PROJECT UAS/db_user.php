<?php

$server = "localhost";
$user = "root";
$password = "root";
$database = "user";

$kon = mysqli_connect($server, $user, $password, $database);

if( !$kon ){
    die("Gagal terhubung dengan database: ". mysqli_connect_error());

}

?>