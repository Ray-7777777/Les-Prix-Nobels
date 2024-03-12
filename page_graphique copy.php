<?php

include("./connexion_base_donnee.php");

// Récupération des années 
$queryYears = "SELECT DISTINCT Année FROM prix_nobel";
$resultYears = $mysqli->query($queryYears);
$years = array();

while ($row = $resultYears->fetch_assoc()) {
    $years[] = $row['Année'];
}
// Récupération des catégories 
// Récupération des pays 
// pays de naissance 
// pays  de décés 

// récupération du nombre de prix Nobel pour chaque catégorie
$queryCatCount = "SELECT c.Nom_catégorie, COUNT(p.id_category) as nbPrixNobel
                 FROM categorie c
                 LEFT JOIN prix_nobel p ON c.Id_catégorie = p.id_category
                 GROUP BY c.Nom_catégorie";

$resultCatCount = $mysqli->query($queryCatCount);
$catCountData = array();

while ($row = $resultCatCount->fetch_assoc()) {
    $catCountData[] = $row;
}

$jsonCatCountData = json_encode($catCountData);

// données de la table categorie
$queryCat = "SELECT Nom_catégorie FROM categorie";
$resultCat = $mysqli->query($queryCat);
$catData = array();
while ($row = $resultCat->fetch_assoc()) {
    $catData[] = $row;
}

// données de catégorie en format JSON
$jsonCatData = json_encode($catData);

?>
<?php
    session_start();
    require_once 'connexion_bd.php';
    $est_connecte = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page Graphique</title>
    <script type="text/javascript" src="../jquery-3.7.1.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="style.css?v=1.1" type="text/css" media="screen" /> -->
    <link rel="stylesheet" href="style.css">
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
                        <a class="nav-link mx-5" id="graphique" href="./page_graphique copy.php" style="color: black;">Graphique</a>
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

    <div class="boite">
        <div class="topBar">
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Type de graphe
               </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('bar')">Barre</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('doughnut')">Circulaire</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('polarArea')">Histogramme</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('line')">Courbe</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('bubble')">Nuage de points</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('box-plot')">Box-plot</a>
                </div>
            </div>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sexe
               </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Masculin</a>
                    <a class="dropdown-item" href="#">Feminin</a>
                </div>
            </div>
            <!-- Année -->
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
            <!-- fin div année -->
              <!-- Categorie -->
              <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Catégorie
                </a>
                <select id="selectCategorie">
                    <?php
                    foreach ($catData as $cat) {
                        echo "<option value=\"" . $cat['Nom_catégorie'] . "\">" . $cat['Nom_catégorie'] . "</option>";
                      
                    }
                    ?>
                </select>
            </div>
            <!-- fin div catégorie -->
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Nombre de prix nobel
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">0-50</a>
                    <a class="dropdown-item" href="#">50-100</a>
                    <a class="dropdown-item" href="#">100-150</a>
                    <a class="dropdown-item" href="#">150-200</a>
                    <a class="dropdown-item" href="#">200-250</a>

                </div>
            </div>
        </div>
        <div class="contenu_graphe">
            <ul>
                <div>
                    <p class="selected-graphe">Le graphe sélectionné</p>
                    <canvas id="graphs"></canvas>
                </div>
            </ul>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
        var ctx = document.getElementById('graphs').getContext('2d');

        // Utilisation des données
        var jsonData = <?php echo $jsonCatCountData; ?>;
        var jsonCatData = <?php echo $jsonCatData; ?>;
        console.log(jsonCatData.map(item => item.Nom_catégorie));

        var labels = jsonCatData.map(item => item.Nom_catégorie);
        var colors = labels.map(getRandomColor);


                console.log(jsonData)

        var config = {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nombre de prix nobels par catégorie',
                    data:jsonData.map(item => item.nbPrixNobel),
                    backgroundColor: colors
                }]
            },
            options: {
                // Configurations d'options
            }
        };

        var myChart = new Chart(ctx, config);

        // changement du type de graphe
        function changerTypeGraphe(type) {
            config.type = type;
            myChart.destroy();
            myChart = new Chart(ctx, config);
        }

        //  couleur aléatoire
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }
    </script>
</body>
</html>
