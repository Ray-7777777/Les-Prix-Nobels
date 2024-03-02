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
<link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>" type="text/css" media="screen" />
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
<div class="boite_connexion">
        <div class="contenu_connexion">
  <form action="traitement.php" method="post" class="text-center">
    <h2 class="titre-connexion">Formulaire de Connexion</h2>

    <div class="form-group row justify-content-center">
        <label for="email" class="col-auto form-label">Email :</label>
        <input type="email" id="email" name="email" class="form-control form-control-sm col-auto custom-input" required>
    </div>

    <div class="form-group row justify-content-center">
        <label for="pseudo" class="col-auto form-label">Pseudo :</label>
        <input type="text" id="pseudo" name="pseudo" class="form-control form-control-sm col-auto custom-input" required>
    </div>

    <div class="form-group row justify-content-center">
        <label for="password" class="col-auto form-label">Mot de passe :</label>
        <input type="password" id="password" name="password" class="form-control form-control-sm col-auto custom-input" required>
    </div>

    <input type="submit" value="Se Connecter" class="btn btn-primary">
</form>




    <?php } ?>
    <li><a id ="inscription" href="inscription.php">inscription</a></li>
     </div>
    </div>
    

</div>

</body>
</html>
