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
    <title>Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha384-MFE7eK2UUVS/sC0iCMkR9X+wcg+BpRGYHQpLMpSWqhEJGu2R0NKQ3f+ieFRps1ID" crossorigin="anonymous">
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
                            <a class="nav-link mx-5" id="graphique" href="./page_graphique copy.php" style="color: black;">Graphique</a>
                        </li>
                        <li class="nav-item">
                            <?php
                            if ($est_connecte) {
                                echo '<a class="nav-link mx-5" id="connexion" href="connexion.php" style="color: black;">Connecté <span class="status-indicator connected"></span></a>';
                            } else {
                                echo '<a class="nav-link mx-5" id="connexion" href="connexion.php" style="color: black;">Connexion <span class="status-indicator disconnected"></span></a>';
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
            <h2 class="titre_article">L'histoire des Prix Nobel</h2>

            <h3 class="sous_titre_article">Origine des Prix Nobel :</h3>
            <p class="p_article">Les Prix Nobel ont été créés par Alfred Nobel, un industriel, ingénieur et inventeur suédois, en 1895. Dans son testament, Nobel a légué la plus grande partie de sa fortune pour créer les prix qui portent maintenant son nom, afin de récompenser les personnes qui ont apporté les plus grands bienfaits à l'humanité dans divers domaines.</p>

            <h3 class="sous_titre_article">Les premiers Prix Nobel :</h3>
            <p class="p_article">Les premiers Prix Nobel ont été décernés en 1901. Ils comprenaient des prix pour la physique, la chimie, la médecine, la littérature et la paix. Depuis lors, les Prix Nobel sont décernés chaque année, sauf pendant les périodes de guerre, selon les volontés de Nobel.</p>

            <h3 class="sous_titre_article">Évolution des catégories de prix :</h3>
            <p class="p_article">Au fil des ans, de nouvelles catégories de prix ont été ajoutées. En 1968, la Banque de Suède a établi le Prix de la Banque de Suède en sciences économiques en mémoire d'Alfred Nobel, souvent appelé le "Prix Nobel d'économie". Ce prix a été officiellement intégré aux Prix Nobel en 1969.</p>

            <h3 class="sous_titre_article">Processus de sélection des lauréats :</h3>
            <p class="p_article">Les lauréats des Prix Nobel sont sélectionnés par des comités spéciaux pour chaque catégorie de prix. Les nominations sont généralement tenues secrètes et les lauréats sont annoncés chaque année en octobre. Les prix sont remis lors d'une cérémonie à Stockholm, en Suède, sauf pour le Prix Nobel de la Paix, qui est remis à Oslo, en Norvège.</p>

            <h3 class="sous_titre_article">Impact et controverse :</h3>
            <p class="p_article">Les Prix Nobel sont parmi les distinctions les plus prestigieuses au monde et ont un impact significatif sur la reconnaissance et la carrière des lauréats. Cependant, les choix des lauréats ne sont pas sans controverse, et il y a eu des critiques sur les processus de sélection, les biais potentiels et les omissions apparentes.</p>

            <h3 class="sous_titre_article">Conclusion :</h3>
            <p class="p_article">L'histoire des Prix Nobel est riche en réalisations remarquables et en contributions exceptionnelles à la science, à la littérature, à la paix et à l'économie. Ces prix continuent d'inspirer l'excellence et l'innovation à travers le monde, et leur impact perdurera pour les générations à venir.</p>
        </div>
    </div>
</body>
</html>
