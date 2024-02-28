<?php
require_once 'connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO utilisateurs (mail, pseudo, mot_de_passe) VALUES (?, ?, ?)";

    // Connexion à la base de données
    $bdd = getBD();
    
    $stmt = $bdd->prepare($sql);

    $stmt->bindParam(1, $email);
    $stmt->bindParam(2, $username);
    $stmt->bindParam(3, $hashed_password);

    if ($stmt->execute()) {
        echo "Inscription réussie !";
        header("Location: index.php"); 
        exit(); 
    } else {
        echo "Erreur lors de l'inscription : " . $stmt->errorInfo()[2]; 
    }

    $stmt->close();
    $bdd = null; 
}
?>
