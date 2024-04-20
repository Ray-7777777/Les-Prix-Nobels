<?php
require_once 'connexion_bd.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn=getBD();
    // Récupérer les données du formulaire
    $message = $_POST['message'];
    $subject = $_POST['Objet'];
    
    // Préparer la requête SQL avec une déclaration préparée
    $sql = "INSERT INTO message (message, objet) VALUES (:message, :objet)";
    $stmt = $conn->prepare($sql);
    
    // Liaison des valeurs et exécution de la requête
    $stmt->bindParam(':message', $message);
    $stmt->bindParam(':objet', $subject);
    
    // Exécuter la requête
    if ($stmt->execute()) {
        echo '<script>alert("Message et objet enregistrés avec succès.");</script>';
        header("refresh:2;url=index.php");
    } else {
        echo '<script>alert("Erreur lors de l\'enregistrement du message et de l\'objet.");</script>';
    }
}

// Fermer la connexion à la base de données (si nécessaire)
$conn = null;
?>
