<?php

include("./connexion_base_donnee.php");
// requete post générique  pour n'importe quelle requ^ete
if(isset($_POST['query'])) {
    $query = $_POST['query'];
    $result = $mysqli->query($query);
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

//==================








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



// recuperation des sexes 
$maleGenderQuery = "SELECT * FROM  nomine WHERE Gender='male'";
$femaleGenderQuery = "SELECT * FROM nomine WHERE Gender='female'";
$maleGenderQueryRes = $mysqli->query($maleGenderQuery);
$maleGenderQueryResArr = array();
while ($row = $maleGenderQueryRes->fetch_assoc()) {
    $maleGenderQueryResArr[] = $row;
}
?>
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
                    <a class="dropdown-item" href="#" id="SelectedMaleGender">Masculin</a>
                    <a class="dropdown-item" href="#" id="SelectedFemaleGender" onclick="displayMaleData()">Feminin</a>
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
                    Pays
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



        var test = [<?php foreach ($years as $year) { echo $year . ','; } ?>];
        console.log(test);
        console.log("delimiter");

        function displayMaleData() {
    var selectElement = document.createElement('select');
    selectElement.setAttribute('id', 'maleSelect');



    <?php 
    foreach ($maleGenderQueryResArr as $item) {
        $prenomNom = addslashes($item['Prénom'] . ' ' . $item['Nom']);
        echo "var option = document.createElement('option');";
        echo "option.value = \"$prenomNom\";";
        echo "option.textContent = \"$prenomNom\";";
        echo "selectElement.appendChild(option);";
    }
    ?>

    return selectElement;
}
document.getElementById('SelectedMaleGender').addEventListener('click', function() {
    var selectElement = displayMaleData();
    document.body.appendChild(selectElement);
});

// à faire la meme pour les pays
function displayFemaleData(){

}
document.getElementById('SelectedFemaleGender').addEventListener('click', function(){
    var selectElement = displayFemaleData();
    document.body.appendChild(selectElement);
})



    </script>
</body>
</html>
