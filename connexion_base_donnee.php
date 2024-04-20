<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "prix_nobel";
$mysqli = new mysqli($host, $user, $password, $database);
if ($mysqli->connect_error) {
    die("La connexion à la base de données a échoué : " . $mysqli->connect_error);
}
?>
