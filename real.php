<?php
include("connexion_base_donnee.php");

// Récupération des années 
$queryYears = "SELECT DISTINCT Année FROM prix_nobel";
$resultYears = $mysqli->query($queryYears);
$years = array();

while ($row = $resultYears->fetch_assoc()) {
    $years[] = $row['Année'];
}

// Récupération des catégories
$queryCategories = "SELECT Nom_catégorie FROM categorie";
$resultCategories = $mysqli->query($queryCategories);
$categories = array();

while ($row = $resultCategories->fetch_assoc()) {
    $categories[] = $row['Nom_catégorie'];  // Récupération  du nom  des categories 

}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Page Graphique</title>
    <script type="text/javascript" src="../jquery-3.7.1.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="style.css?v=1.1" type="text/css" media="screen" /> -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="entete">
        <h1>PRIX NOBEL</h1>

        <ol class="menu" id="menu">
            <li><a id="accueil" href="index.php">Accueil</a></li>
            <li><a id="recherche" href="recherche.php">Recherche</a></li>
            <li><a id="graphique" href="./real.php">Graphique 1</a></li>
            <li><a id="graphique" href="./page_graphique copy.php">Graphique 2</a></li>
            <li><a id="connexion" href="connexion.php">Connexion</a></li>
        </ol>
    </div>

    <div class="boite">
        <div class="topBar">

                    <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Type de graphe
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" onclick="selectChartType('bar')">Barre</a>
                    <a class="dropdown-item" href="#" onclick="selectChartType('doughnut')">Circulaire</a>
                    <a class="dropdown-item" href="#" onclick="selectChartType('polarArea')">Histogramme</a>
                    <a class="dropdown-item" href="#" onclick="selectChartType('line')">Courbe</a>
                    <a class="dropdown-item" href="#" onclick="selectChartType('bubble')">Nuage de points</a>
                    <a class="dropdown-item" href="#" onclick="selectChartType('box-plot')">Box-plot</a>
                </div>
            </div>
                        <div class="dropdown">
                <label for="selectYear">Année:</label>
                <select id="selectYear">
                    <?php
                    foreach ($years as $year) {
                        echo "<option value=\"$year\">$year</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="dropdown">
                <label for="selectGender" class="btn btn-secondary dropdown-toggle"  role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sexe
                </label>
                <select id="selectGender"   class="dropdown-menu">
                    <option value="male" class="dropdown-item">male</option>
                    <option value="female" class="dropdown-item">female</option>
                </select>
            </div>


            <!-- <div class="dropdown">
                <label for="selectLimit">Limite:</label>
                <select id="selectLimit">
                    <option value="50">0-50</option>
                    <option value="100">50-100</option>
                    <option value="150">100-150</option>
                    <option value="200">150-200</option>
                    <option value="250">200-250</option>
                </select>
            </div> -->

            <button onclick="afficherDonnees()">Afficher</button>
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
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    var ctx = document.getElementById('graphs').getContext('2d');
    var myChart;
    var selectedChartType = 'bar';
    function afficherDonnees() {
        var selectedYear = document.getElementById("selectYear").value;
        var selectedGender = document.getElementById("selectGender").value;
        var selectedLimit = document.getElementById("selectLimit").value;

        // Exécution de la requête avec les filtres sélectionnés
        
        var query = "SELECT p.*, n.Gender FROM prix_nobel p " +
            "INNER JOIN nomine n ON p.id_prix_nobels = n.`Id-nominé` " +
            "WHERE p.Année = " + selectedYear + " AND n.Gender = '" + selectedGender + "' LIMIT " + selectedLimit;

        $.ajax({
            type: "POST",
            url: "requete.php",  
            data: {query: query},
            success: function(response) {
                try {

                    console.log(response)
        var jsonData = JSON.parse(response);
        updateChart(jsonData, selectedChartType);// mis à jour du graphique
            } catch (e) {
        console.error("Erreur lors de l'analyse JSON:", e);
    }},
            error: function(error) {
                console.log(error);
            }
        });
    }

   
    function updateChart(data, chartType) {
    console.log(data);

   // Suppression du graphe 
    if (myChart) {
        myChart.destroy();
    }

    // type de graphique en fonction de la sélection
    switch (chartType) {
        case 'bar':
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.id_nominé),
                    datasets: [{
                        label: 'Diagramme en barre',
                        data: data.map(item => item.id_prix_nobels),
                    }]
                },
            });
            break;
        case 'doughnut':
            //  à personnaliser
            break;
        case 'polarArea':
               //  à personnaliser
            break;
        case 'line':
          //  à personnaliser
            break;
        case 'bubble':
               //  à personnaliser 
            break;
        case 'box-plot':
            //  à personnaliser  
            break;
        default:
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.id_nominé),
                    datasets: [{
                        label: 'Nom du dataset',
                        data: data.map(item => item.id_prix_nobels),
                    }]
                },
            });
    }
}


function selectChartType(type) {
        selectedChartType = type;
        document.getElementById("dropdownMenuLink").innerText = "Type de graphe: " + type.charAt(0).toUpperCase() + type.slice(1);
    }
</script>

</body>
</html>
