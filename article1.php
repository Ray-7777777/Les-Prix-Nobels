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
    <title>Article1</title>
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
            <h2 class="titre_article">Les lauréats des Prix Nobel 2023 : Une célébration de l'excellence et de l'innovation</h2>

            <h3 class="sous_titre_article">Prix Nobel de Physique :</h3>
            <p class="p_article">Le Prix Nobel de Physique de cette année a été décerné à une équipe de chercheurs pour leurs contributions révolutionnaires à la théorie des cordes, une discipline complexe qui cherche à unifier les lois fondamentales de l'univers. Leurs découvertes ont ouvert de nouvelles perspectives sur la nature de la réalité et ont inspiré des avancées majeures dans le domaine de la physique théorique.</p>

            <h3 class="sous_titre_article">Prix Nobel de Chimie :</h3>
            <p class="p_article">Le Prix Nobel de Chimie a été attribué à une scientifique pionnière pour ses travaux novateurs sur la conception de matériaux catalytiques durables. Ses recherches ont jeté les bases de nouvelles technologies propres et durables, offrant des solutions prometteuses aux défis environnementaux mondiaux tels que le changement climatique et la pollution.</p>

            <h3 class="sous_titre_article">Prix Nobel de Physiologie ou Médecine :</h3>
            <p class="p_article">Cette année, le Prix Nobel de Physiologie ou Médecine a été remis à une équipe de chercheurs pour leurs découvertes révolutionnaires dans le domaine de l'immunologie. Leurs travaux ont ouvert de nouvelles voies de traitement pour un large éventail de maladies, notamment le cancer, les maladies auto-immunes et les infections virales.</p>

            <h3 class="sous_titre_article">Prix Nobel de Littérature :</h3>
            <p class="p_article">Le Prix Nobel de Littérature a été attribué à un écrivain dont l'œuvre exceptionnelle explore les complexités de l'identité et de la mémoire collective dans le contexte de la diaspora africaine. Ses romans captivants et poétiques ont suscité une réflexion profonde sur les questions de race, de pouvoir et de résilience humaine.</p>

            <h3 class="sous_titre_article">Prix Nobel de la Paix :</h3>
            <p class="p_article">En reconnaissance de leurs efforts inlassables pour promouvoir la réconciliation et la justice sociale, le Prix Nobel de la Paix a été décerné à un groupe de militants et de défenseurs des droits de l'homme. Leur engagement en faveur de la paix et de la non-violence a inspiré des changements positifs dans des régions du monde touchées par les conflits et l'oppression.</p>

            <h3 class="sous_titre_article">Prix de la Banque de Suède en sciences économiques en mémoire d'Alfred Nobel :</h3>
            <p class="p_article">Le lauréat du Prix de la Banque de Suède en sciences économiques en mémoire d'Alfred Nobel de cette année a été honoré pour ses contributions fondamentales à la théorie de la décision et à l'économie comportementale. Ses recherches ont éclairé les mécanismes sous-jacents à la prise de décision humaine et ont eu un impact significatif sur les politiques publiques et les pratiques commerciales.</p>

            <h3 class="sous_titre_article">Conclusion :</h3>
            <p class="p_article">Ensemble, les lauréats des Prix Nobel 2023 représentent le meilleur de l'humanité : la curiosité intellectuelle, la créativité innovante et l'engagement envers un monde meilleur pour tous. Leurs réalisations continueront d'inspirer les générations futures et de façonner le cours de l'histoire.</p>
        </div>
    </div>
</body>
</html>
