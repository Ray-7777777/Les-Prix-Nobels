<?php
include("connexion_base_donnee.php");
// Récupération de la requête
$query = $_POST['query'];

// Exécution la requête SQL
$result = $mysqli->query($query);

if (!$result) {
    die("Erreur dans la requête SQL: " . $mysqli->error);
}


$data = array();
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

// Fermeture de la connexion à la base de données
$mysqli->close();

echo json_encode($data);
?>
