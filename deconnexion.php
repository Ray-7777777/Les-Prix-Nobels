<?php
// Démarrer la session
session_start();

// Détruire toutes les données de la session
session_destroy();

// Rediriger vers la page d'accueil ou toute autre page de votre choix
header("Location: connexion.php");
exit();
?>