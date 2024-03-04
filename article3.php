<?php
    session_start();
    require_once 'connexion_bd.php';
    $est_connecte = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article3</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	 <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>" type="text/css" media="screen" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    <style>
    
    </style>
</head>
<body>
    <div id="entete">
    <a href="index.php" style="text-decoration: none;">
        <h1><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
	</a>
    </div>
<div class="menu-article" style="width: 100%; background-color: white;">
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
    <div class="boite_article">
        <div class="contenu_article">
            <h2 class="titre_article">L'impact historique et social des Prix Nobel de la Paix</h2>

            <h3 class="sous_titre_article">Origine des Prix Nobel de la Paix :</h3>
            <p class="p_article">Le Prix Nobel de la Paix a été créé par Alfred Nobel en 1895, en même temps que les autres Prix Nobel, dans le but de récompenser les individus ou les organisations qui ont œuvré pour la promotion de la paix et de la réconciliation.</p>

            <h3 class="sous_titre_article">Lauréats emblématiques :</h3>
            <p class="p_article">Parmi les lauréats les plus emblématiques du Prix Nobel de la Paix figurent des personnalités telles que Martin Luther King Jr., Nelson Mandela, Malala Yousafzai et le Dalaï Lama. Leurs actions et leur engagement ont eu un impact profond sur la société et ont contribué à promouvoir la paix dans le monde.</p>

            <h3 class="sous_titre_article">Influence sur l'histoire contemporaine :</h3>
            <p class="p_article">Les lauréats du Prix Nobel de la Paix ont souvent joué un rôle crucial dans des événements historiques majeurs, tels que la lutte pour les droits civils aux États-Unis, la fin de l'apartheid en Afrique du Sud et la lutte pour l'éducation des filles dans le monde entier.</p>

            <h3 class="sous_titre_article">Défis et controverses :</h3>
            <p class="p_article">Bien que le Prix Nobel de la Paix soit généralement salué comme une reconnaissance méritée du travail de pacification, il a également été critiqué pour ses choix controversés et politiquement motivés. Certains lauréats ont été accusés de ne pas avoir répondu aux critères stricts de Nobel en matière de promotion de la paix.</p>

            <h3 class="sous_titre_article">Impact sur la société contemporaine :</h3>
            <p class="p_article">Les lauréats du Prix Nobel de la Paix continuent d'inspirer des générations entières à s'engager pour la paix, la justice sociale et les droits de l'homme. Leurs actions démontrent le pouvoir de l'individu à apporter des changements significatifs dans le monde et à construire un avenir meilleur pour tous.</p>

            <h3 class="sous_titre_article">Conclusion :</h3>
            <p class="p_article">Les Prix Nobel de la Paix ont joué un rôle crucial dans la reconnaissance et la promotion de la paix dans le monde. À travers les actions et les réalisations des lauréats, ils ont façonné l'histoire contemporaine et continuent d'inspirer des efforts pour un monde plus pacifique et plus juste.</p>
        </div>
    </div>
</body>
</html>
