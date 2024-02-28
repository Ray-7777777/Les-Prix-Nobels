<?php
// Démarrer la session
session_start();

$est_connecte = isset($_SESSION['user_id']);


?>
    <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css?v=1.1" type="text/css" media="screen" />
</head>
<body>
<div id="entete">
<h1>PRIX NOBEL</h1>

<ol class="menu" id="menu">

            <li><a id="accueil" href="index.php">Accueil</a></li>
            <li><a id="recherche" href="recherche.php">Recherche</a></li>
            <li><a id="graphique" href="./real.php">Graphique 1</a></li>
            <li><a id="graphique" href="./page_graphique copy.php">Graphique 2</a></li>
            <li><a id="connexion" href="connexion.php">Connexion</a></li>

</ol>
</div>
    <div >
    <?php
    // Si l'utilisateur est connecté, afficher un message et proposer de se déconnecter
    if ($est_connecte) {
        echo "<p>Vous êtes déjà connecté. <a href='deconnexion.php'>Se déconnecter</a></p>";
    } else {
        // Si l'utilisateur n'est pas connecté, afficher le formulaire de connexion
    
 
?>
    <form action="traitement.php" method="post">
        <h2>Formulaire de Connexion</h2>
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" required>

        <label for="pseudo">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" required>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required>

        <input type="submit" value="Se Connecter">
    </form>
    <?php } ?>
    <li><a id ="inscription" href="inscription.php">inscription</a></li>
    

</div>

</body>
</html>
