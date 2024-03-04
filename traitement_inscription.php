<?php

require_once 'connexion_bd.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Vérifier si l'email existe déjà dans la base de données
    $sql_check_email = "SELECT COUNT(*) AS count FROM utilisateurs WHERE mail = ?";
    $bdd = getBD();
    $stmt_check_email = $bdd->prepare($sql_check_email);
    $stmt_check_email->execute([$email]);
    $result = $stmt_check_email->fetch(PDO::FETCH_ASSOC);
    $email_exists = $result['count'] > 0;

    if ($email_exists) {
        // Si l'email est déjà utilisé, afficher un message d'erreur et rediriger après 5 secondes
        echo "<p>Email déjà utilisé. Vous serez redirigé vers la page de connexion dans 5 secondes.</p>";
        header("refresh:5;url=connexion.php");
        exit();
    } else {
        // Si l'email n'est pas utilisé, procéder à l'inscription
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateurs (mail, mot_de_passe) VALUES (?, ?)";
        $stmt = $bdd->prepare($sql);
        $stmt->execute([$email, $hashed_password]);

        // Enregistrement de l'utilisateur dans la session
        $_SESSION['user_id'] = $bdd->lastInsertId();

        // Redirection vers la page d'accueil ou une autre page
        header("Location: index.php");
        exit();
    }
}
?>
