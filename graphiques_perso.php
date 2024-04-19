<?php
    session_start();
    require_once 'connexion_bd.php';
    $est_connecte = isset($_SESSION['user_id']);

    $connexion = getBD();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css?v=<?php echo filemtime('style.css'); ?>" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Reem+Kufi+Fun:wght@400..700&display=swap" rel="stylesheet">
</head>
<body id='body_wiki'>
    <div id="entete">
    <a href="index.php" style="text-decoration: none;">
        <h1 id = 'h1_wiki' class="oswald-font"><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
    </a>
	</div>

    <div id = 'menu_wiki' class="menu" style="width: 100%;">
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
                        <a class="nav-link mx-5" id="graphique" href="graphique.php" style="color: black;">Graphique</a>
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
    <div class="boite_graphiques">
    <div>
    <h2 id="titre_grap">Graphique personnalisé</h2>
    </div>
        <div class="topBar">
            <div class="dropdown" id="menuG">
            
                <a id ="boutton_graph" class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Type de graphe    
               </a>
                <div class="dropdown-menu">
                    <button class="dropdown-item typeG" type="button" href="#" id="Barre">Barre</button>
                    <button class="dropdown-item typeG" type="button" href="#" id="Circulaire">Circulaire</button>
                </div>
            </div>
            
            <div id="varX">
                <h4 class="titreVariables">Variables pour X :</h4>
                <!-- Bouton Sexe -->
                <button type="button" class="btn btn-warning btnVar" data-axe="X" data-nb="menu1" data-cat="Gender" id="GenderX" href="#" role="button" aria-haspopup="true" aria-expanded="false">Sexe</button>
                <!-- Bouton Année -->
                <button type="button" class="btn btn-warning btnVar" data-axe="X" data-cat="Année" data-nb="menu2" id="AnnéeX" href="#" role="button" aria-haspopup="true" aria-expanded="false">Année</button>
                <!-- Categorie -->
                <button type="button" class="btn btn-warning btnVar" data-axe="X" data-cat="Category" data-nb="menu3" id="CategoryX" href="#" role="button" aria-haspopup="true" aria-expanded="false">Catégorie</button>
                <!-- Naissance -->
                <button type="button" class="btn btn-warning btnVar" href="#" data-axe="X" data-cat="Naissance" data-nb="menu4" id="NaissanceX" role="button" aria-haspopup="true" aria-expanded="false">Pays de naissance</button>
                <!-- Décès -->
                <button type="button" class="btn btn-warning btnVar" href="#" data-axe="X" data-cat="Décès" data-nb="menu5" id="DécèsX" role="button" aria-haspopup="true" aria-expanded="false">Pays de décès</button>
                <!-- NombrePrix -->
                <button type="button" class="btn btn-warning btnVar" href="#" data-axe="X" data-cat="NombrePrix" data-nb="menu6" id="NombrePrixX" role="button" aria-haspopup="true" aria-expanded="false">Nombre de prix Nobel</button>
            </div>
            <div id="varY">
                <h4 class="titreVariables" id="titreY" >Variables pour Y :</h4>
                <button type="button" class="btn btn-warning btnVar" data-nb="menu7" data-axe="Y" data-cat="Gender" id="GenderY" href="#" role="button" aria-haspopup="true" aria-expanded="false">Sexe</button>
                <button type="button" class="btn btn-warning btnVar" data-axe="Y" data-nb="menu8" data-cat="Année" id="AnnéeY" href="#" role="button" aria-haspopup="true" aria-expanded="false">Année</button>
                <button type="button" class="btn btn-warning btnVar" data-axe="Y" data-nb="menu9" data-cat="Category" id="CategoryY" href="#" role="button" aria-haspopup="true" aria-expanded="false">Catégorie</button>
                <button type="button" class="btn btn-warning btnVar" data-axe="Y" href="#" data-cat="Naissance" data-nb="menu10" id="NaissanceY" role="button" aria-haspopup="true" aria-expanded="false">Pays de naissance</button>
                <button type="button" class="btn btn-warning btnVar" data-axe="Y" href="#" data-cat="Décès" data-nb="menu11" id="DécèsY" role="button" aria-haspopup="true" aria-expanded="false">Pays de décès</button>
                <button type="button" class="btn btn-warning btnVar" data-axe="Y" href="#" data-cat="NombrePrix" data-nb="menu12" id="NombrePrixY" role="button" aria-haspopup="true" aria-expanded="false">Nombre de prix Nobel</button>
            </div>
        </div>
        <div class="d-flex justify-content-center">
    <div class="contenu_graphe">
        <div id="activeVar">
            <h6 id="varSelect">Options sélectionnées:</h6>
            <div class="activeVar activeGraph"><div id="activeGraph"></div></div>
            <div class="activeVar" id="activeX"><div id="varActX"></div></div>
            <div class="activeVar" id="activeY"><div id="varActY"></div></div>
        </div>
        <div id="divGraphiques">
            <canvas id="graphs" width="800" height="600"></canvas>
        </div>
    </div>
</div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="graphiques.js"></script> 

</body>
</html>