<?php
include("connexion_base_donnee.php");
$query = $_POST['query'];
$result = $mysqli->query($query);

if (!$result) {
    die("Erreur dans la requête SQL: " . $mysqli->error);
}


$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$mysqli->close();

echo json_encode($data);
?>
