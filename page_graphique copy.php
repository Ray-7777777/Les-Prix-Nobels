<?php include("queries.php");?>;

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
<body>
    <div id="entete">
    <a href="index.php" style="text-decoration: none;">
        <h1 class="oswald-font"><span class="text-stroke" style="color : black;">PRIX NOBEL</span></h1>
    </a>
	</div>

<div class="menu" style="width: 100%;">
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

    <div class="boite">
        <div class="topBar">
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Type de graphe
               </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" data-chart-type="bar">Barre</a> 
                    <!-- <a class="dropdown-item" href="#" >Circulaire</a> -->
                    <a class="dropdown-item" href="#" data-chart-type="line">Courbe</a>
                    <a class="dropdown-item" href="#" id="selectYearCam">Diagramme en cambert</a>
                    <a class="dropdown-item" href="#" data-chart-type=>Nuage de points</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('box-plot')">Box-plot</a> 
                </div>
            </div>
            <div class="dropdown"> 
                <a class="btn btn-secondary dropdown-toggle " id="Gender" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Axe X </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Catégorie</a>
                    <a class="dropdown-item" href="#" id="pays">Pays d'Origine des Organisations</a>
                    <!-- <a class="dropdown-item" href="#" id="historique">Historique</a> -->
                    <a class="dropdown-item" href="#">Pays de décès</a>
                    <a class="dropdown-item" href="#">Année</a>
                    <a class="dropdown-item" href="#" id="SEXE">Sexe</a>
                    <a class="dropdown-item" href="#" id="organisation">Organisation</a>
                </div>
            </div>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle " id="Gender" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Axe Y</a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Nombre de prix Nobel</a>
                </div>
            </div>
            <div class="dropdown">
      <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Année
      </a>
      <select id="selectYear">
          <?php
          foreach ($years as $year) {
              echo "<option value=\"$year\">$year</option>";
          }
          ?>
      </select>
  </div>
        </div>
        <!-- le contenu du canvas du graphe  -->
        <div class="contenu_graphe">


        
            <ul>
                <div>
                    <canvas id="graphs"></canvas>
                </div>
            </ul>
        </div>
        <!-- fin du contenu du canvas -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="query_data_graph.js"></script>
   
<script>
       var donneesHistorique = <?php echo $donneesJSON_Historique; ?>;   
var organisation = <?php echo json_encode($var_Array_Organisation); ?>;
var categories = <?php echo $jsonCategories; ?>;
var datasets = <?php echo $jsonDatasets; ?>;
var maleData = <?php echo $jsonMaleData; ?>;
var femaleData= <?php echo $jsonFemaleData; ?>;
var years = <?php echo json_encode($years); ?>;
var decennies = <?php echo json_encode($decennies); ?>;
var impactData = <?php echo json_encode($jsonImpactData); ?>;
var queryCam =  <?php echo json_encode($queryCamArray); ?>; 
var donneesPaysOrganisation = <?php echo ($paysOrganisationsJSON); ?>;

 
    </script>






</body>

</html>