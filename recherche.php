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
                            <a class="nav-link mx-5" id="graphique" href="./real.php" style="color: black;">Graphique 1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-5" id="graphique" href="./page_graphique copy.php" style="color: black;">Graphique 2</a>
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
	<div class="boite">
		<div class="topBar">
			<div id="search">
				<nav class="navbar navbar-light bg-light rounded-pill col-lg-12">
  					<div class="container-fluid">
    					<form class="d-flex" action="rechercher.php" method="post">
      						<input class="form-control me-2 col-lg-12" type="search" placeholder="Rechercher" aria-label="Rechercher" id="bar" name="recherche">
      						<button class="btn btn-outline-success" type="submit" id="boutonRecherche">Recherche</button>
    					</form>
  					</div>
				<div class="filters">
				</div>
				<div id="activeFilters">
				</div>
				</nav>
			</div>
			<div class="dropdown">
  				<a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    				Filtres
 				</a>

  				<div class="dropdown-menu">
    				<button class="dropdown-item BFiltre" type="button" id="Bnationalite" name="Born country">Nationalité</button>
   					<button class="dropdown-item BFiltre" type="button" id="Bgenre" name="Gender">Genre</button>
  				</div>
			</div>
		</div>
		<div class="contenu">
		</div>
	</div>
	<script src="filtres.js"></script>
	<script src="recherche.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>