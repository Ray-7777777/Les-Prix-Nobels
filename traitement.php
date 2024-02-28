<?php
require_once 'connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $pseudo = htmlspecialchars($_POST['pseudo']);

    // Connexion à la base de données
    $conn = getBD();

    // Votre logique de traitement ici...
    
    $sql = "SELECT id_utilisateur, mot_de_passe FROM utilisateurs WHERE mail=?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        if ($row) {
            if (password_verify($password, $row['mot_de_passe'])) {
                // Mot de passe correct
                session_start();
                $_SESSION['user_id'] = $row['id_utilisateur']; // Stocker l'ID de l'utilisateur dans la session
                $_SESSION['email'] = $email; // Stocker l'email de l'utilisateur dans la session
                $_SESSION['pseudo'] = $pseudo; // Stocker le pseudo de l'utilisateur dans la session
                header("Location: index.php"); // Rediriger vers la page d'accueil
                exit(); // Arrêter le script
            } else {
                echo "Mot de passe incorrect.";
            }
        } else {
            echo "Aucun utilisateur trouvé avec cet email.";
        }
    } else {
        echo "Une erreur est survenue lors de la préparation de la requête.";
    }
}
?>
