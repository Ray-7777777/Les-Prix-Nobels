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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
	 <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	 <link href="https://fonts.googleapis.com/css2?family=Oswald&display=swap" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao+Looped:wght@100..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Roboto+Condensed:ital,wght@0,100..900;1,100..900&family=Roboto+Slab:wght@100..900&family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
	 <link href="https://fonts.googleapis.com/css2?family=Reem+Kufi+Fun:wght@400..700&display=swap" rel="stylesheet">	 

</head>
<body id="body_recherche">
    <div id="entete">
    <a href="index.php" style="text-decoration: none;">
        <h1 class="oswald-font" id="menu_recherche"><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
    </a>
	</div>

<div class="menu" id="menu_recherche" style="width: 100%;">
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
	<div class="boite_recherche">
		<div class="topBar">
			<div id="search">
				<nav class="navbar navbar-light bg-light rounded-pill col-lg-12">
  					<div class="container-fluid recherche">
    					<form class="d-flex recherche" action="rechercher.php" method="post">
      						<input class="form-control me-2 col-lg-12" type="search" placeholder="Rechercher" aria-label="Rechercher" id="bar" style="border: 1px solid black;border-radius: 30px;box-shadow: 0 0 5px rgba(1, 1, 1, 0.4);" name="recherche">
      						<button style="border: 1px solid black; border-radius: 150px; box-shadow: 0 0 5px rgba(1, 1, 1, 0.4); background-color: transparent;" 
        class="btn btn-outline-success" type="submit" id="boutonRecherche"
        onmouseover="this.style.backgroundColor='#cfd1cd';" 
        onmouseout="this.style.backgroundColor='transparent';">
    <img src="Images/loupe.png" width="20" height="20">
</button>
<div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filtres
                </a>
                <div class="dropdown-menu">
                    <button class="dropdown-item BFiltre" type="button" id="Bnationalite" name="Born_country">Nationalité</button>
                    <button class="dropdown-item BFiltre" type="button" id="Bgenre" name="Gender">Genre</button>
                </div>
            </div>

    					</form>
  					</div>
				<div class="filters" ">
				</div>
				<div id="activeFilters">
				</div>
				</nav>
			</div>
			
		</div>
		<div class="contenu">
		</div>
	</div>
	<script src="filtres.js"></script>
	<script src="recherche.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>

    function envoyerHistorique(pageURL, id_prix_nobel) {
    // Vérifier si l'utilisateur est connecté
    var estConnecte = <?php echo $est_connecte ? 'true' : 'false'; ?>;
    
    // Vérifier si la page actuelle est un article
    var estPageArticle = pageURL.includes("article_wikipedia.php");

    if (estConnecte && estPageArticle) {
        // Créer une requête AJAX
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "enregistrer_historique.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
                console.log(xhr.responseText);
            }
        };
        // Envoyer les données à votre script PHP
        xhr.send("pageURL=" + encodeURIComponent(pageURL) + "&id_prix_nobel=" + encodeURIComponent(id_prix_nobel));
    }
}


    window.addEventListener("load", function() {
        // Envoyer l'historique lorsque la page est chargée
        envoyerHistorique(window.location.href, <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>);
    });
        
    // Écouteur d'événement pour le changement de page (navigation)
    window.addEventListener("beforeunload", function() {
        // Envoyer l'historique lorsque l'utilisateur quitte la page
        envoyerHistorique(window.location.href, <?php echo isset($_GET['id']) ? $_GET['id'] : 'null'; ?>);
    });


});


</script>
<script>
$(document).ready(function() {
    // Afficher seulement le texte avant le premier "=="
    $(".minibio").each(function() {
        var text = $(this).text();
        var index = text.indexOf("=="); // Trouver l'index du premier "=="
        if (index !== -1) {
            text = text.substring(0, index); // Extraire le texte avant le premier "=="
        }
        $(this).text(text);
    });
});
</script>



</body>
</html>