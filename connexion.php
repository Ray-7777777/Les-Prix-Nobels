<?php
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <div id="entete">
        <a href="index.php" style="text-decoration: none;">
            <h1><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
        </a>
    </div>
    <div class="menu" style="width: 100%; background-color: white;">
        <nav class="navbar navbar-expand-lg navbar-light justify-content-start px-0">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav w-100 justify-content-between">
                        <li class="nav-item">
                            <a class="nav-link mx-5" id="accueil" href="index.php" style="color: black;">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-5" id="recherche" href="recherche.php" style="color: black;">Recherche</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-5" id="graphique" href="./real.php" style="color: black;">Graphique 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-5" id="graphique" href="./page_graphique copy.php" style="color: black;">Graphique 2</a>
                        </li>
                        <li class="nav-item">
                            <?php
                            if ($est_connecte) {
                                echo '<a class="nav-link mx-5" id="connexion" href="connexion.php" style="color: black;">Connecté</a>';
                            } else {
                                echo '<a class="nav-link mx-5" id="connexion" href="connexion.php" style="color: black;">Connexion</a>';
                            }
                            ?>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="boite_connexion">
        <div class="contenu_connexion">
            <?php
            if ($est_connecte) {
                echo "<p>Vous êtes déjà connecté. <a href='deconnexion.php'>Se déconnecter</a></p>";
            } else {
            ?>
                <form action="traitement.php" method="post" class="text-center">
                    <h2 class="titre-connexion">Formulaire de Connexion</h2>
                    <div id="formu">

                    <div id="email" class="form-group row justify-content-center">
                        <label for="email" class="form-group col-form-label">Email :</label>
                        <div class="col-auto">
                            <input type="email" id="email" name="email" class="form-control form-control-sm custom-input" required>
                        </div>
                    </div>

                    <div id="motdepasse"class="form-group row justify-content-center">
                        <label for="password" class="form-group col-form-label">Mot de passe :</label>
                        <div class="col-auto">
                            <input type="password" id="password" name="password" class="form-control form-control-sm custom-input" required>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="submit" value="Se Connecter" class="btn btn-primary mt-3">
                    </div>
                    </div>
                </form>
            <?php } ?>
            <div class="text-center">
            <a id="inscription" href="inscription.php" style="color: black;">Inscription</a>
        </div>
        </div>
    </div>

</body>

</html>
