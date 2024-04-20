<?php

require_once 'connexion_bd.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $conn = getBD();

    $sql = "SELECT id_utilisateur, mot_de_passe FROM utilisateurs WHERE mail=?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->execute([$email]);
        $row = $stmt->fetch();

        if ($row) {
            if (password_verify($password, $row['mot_de_passe'])) {
                session_start();
                $_SESSION['user_id'] = $row['id_utilisateur']; 
                $_SESSION['email'] = $email; 
                echo 'success';
                exit(); 
            } else {
                echo 'error';
                exit();
            }
        } else {
            echo "Aucun utilisateur trouvé avec cet email. Vous serez redirigé vers la page de connexion dans 3 secondes.";
            header("refresh:3;url=connexion.php");
            exit();
        }
    } else {
        echo "Une erreur est survenue lors de la préparation de la requête. Vous serez redirigé vers la page de connexion dans 5 secondes.";
        header("refresh:5;url=connexion.php");
        exit();
    }
}

?>
