<?php
include("connexion_base_donnee.php");
// Récupération des années 
$queryYears = "SELECT DISTINCT Année FROM prix_nobel   ORDER BY Année ASC" ;
$resultYears = $mysqli->query($queryYears);
$years = array();

while ($row = $resultYears->fetch_assoc()) {
    $years[] = $row['Année'];
}



//=============================================================
// requete pour la visualisation des impacts des prix nobels par pays et Catégorie 
$impactQuery = "SELECT DISTINCT
Nom_catégorie, 
`Born Country` AS Pays, 
Gender, 
COUNT(*) AS TotalLaureats
FROM 
nomine
JOIN 
prix_nobel ON nomine.`Id-nominé` = prix_nobel.id_nominé
JOIN 
categorie ON prix_nobel.id_category = categorie.Id_catégorie
GROUP BY 
Nom_catégorie, Pays, Gender
ORDER BY 
Nom_catégorie, Pays;
";

$resultImpact = $mysqli->query($impactQuery);
$impactData = [];

while ($row = $resultImpact->fetch_assoc()) {
    $impactData[] = $row;
}

$jsonImpactData = json_encode($impactData);


//=============================================================
// récupération du nombre de prix Nobel pour chaque catégorie
$queryCatCount = "SELECT 
    p.Année, 
    c.Nom_catégorie, 
    n.Gender, 
    COUNT(p.id_nominé) AS nbPrixNobel
FROM 
    prix_nobel p
JOIN 
    nomine n ON p.id_nominé = n.`Id-nominé`
JOIN 
    categorie c ON c.Id_catégorie = p.id_category
GROUP BY 
    p.Année, 
    c.Nom_catégorie, 
    n.Gender
ORDER BY 
    p.Année ASC, 
    c.Nom_catégorie ASC;";






$resultCatCount = $mysqli->query($queryCatCount);
$catCountData = array();

while ($row = $resultCatCount->fetch_assoc()) {
    $catCountData[] = $row;
}
//conversion des donnees pour qu'elles soient facilement utilissables dans le graphes 
$categories = [];
$maleData= [];
$femaleData = [];
$datasets = [];

foreach($catCountData as $data ){
    $annne = $data['Année'];
    $categorie = $data['Nom_catégorie'];
    $nbPrixNobel = $data['nbPrixNobel'];
    $gender = $data['Gender'];

    if ($gender == 'male') {
        $maleData[$annne][$categorie] = (isset($maleData[$annne][$categorie]) ? $maleData[$annne][$categorie] : 0) + $nbPrixNobel;
    } else if ($gender == 'female') {
        $femaleData[$annne][$categorie] = (isset($femaleData[$annne][$categorie]) ? $femaleData[$annne][$categorie] : 0) + $nbPrixNobel;
    }

    if (!in_array($categorie, $categories)){
        $categories[] = $categorie;
    }
    if(!isset($datasets[$annne])){
        $datasets[$annne] = [
            'label' => $annne,
            'data' => array_fill(0, count($categories), 0),
          
        ];
    }
    $index = array_search($categorie, $categories);
    $datasets[$annne]['data'][$index] = $nbPrixNobel;
}
$datasets = array_values($datasets);
$jsonCategories = json_encode($categories);
$jsonMaleData = json_encode($maleData);
$jsonFemaleData = json_encode($femaleData);
$jsonDatasets = json_encode($datasets);
//=========================================================================================

// données de la table categorie
$queryCat = "SELECT Nom_catégorie FROM categorie";
$resultCat = $mysqli->query($queryCat);
$catData = array();
while ($row = $resultCat->fetch_assoc()) {
    $catData[] = $row;
}

// données de catégorie en format JSON
$jsonCatData = json_encode($catData);



// recuperation des Sexes 
$maleGenderQuery = "SELECT * FROM  nomine WHERE Gender='male'";
$femaleGenderQuery = "SELECT * FROM nomine WHERE Gender='female'";
//male query fetching from the database
$maleGenderQueryRes = $mysqli->query($maleGenderQuery);
$maleGenderQueryResArr = array();
while ($row = $maleGenderQueryRes->fetch_assoc()) {
    $maleGenderQueryResArr[] = $row;
}
//female query fetching from the database 
$femaleGenderQueryRes = $mysqli->query($femaleGenderQuery);
$femaleGenderQueryArr = array();
while ($row = $femaleGenderQueryRes->fetch_assoc()){
    $femaleGenderQueryArr[] = $row;
}

// recuperation des pays : pays de naissance et pays de décés
$birthCountry ="SELECT   DISTINCT `Born country` FROM nomine"; 
$deathCountry = "SELECT  DISTINCT `Died country` FROM nomine";
// birth country  query fetching from the database
$birthCountryQuery = $mysqli->query($birthCountry);
$birthCountryArrResponseQuery = array();
while ($row = $birthCountryQuery->fetch_assoc()){
    $birthCountryArrResponseQuery[] = $row;
}
// died country query fetching fromm the database
$deathCountryQuery = $mysqli->query($deathCountry);
$deathCountryArrResponseQuery = array();
while ($row = $deathCountryQuery->fetch_assoc()){
    $deathCountryArrResponseQuery[] = $row;
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
                    <a class="dropdown-item" href="#" id="CircularDiagram">Circulaire</a>
                    <a class="dropdown-item" href="#" id="selectYearCam">Histogramme</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('line')">Courbe</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('bubble')">Nuage de points</a>
                    <a class="dropdown-item" href="#" onclick="changerTypeGraphe('box-plot')">Box-plot</a>
                </div>
            </div>
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle " id="Gender" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Sexe
               </a>
                  <!-- <div class="dropdown-menu">
                    <a class="dropdown-item" href="#" id="SelectedMaleGender" onclick="displayMaleData()">Masculin</a>
                    <a class="dropdown-item" href="#" id="SelectedFemaleGender" onclick="displayFemaleData()">Feminin</a>
                </div> -->
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
                <a class="btn btn-secondary dropdown-toggle" href="#" id="Country" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Pays
                </a>
                <!--<div class="dropdown-menu">
                    <a class="dropdown-item" href="#" id="SelectedBornCountry" onclick="displayBornCountry()">de naissance</a>
                    <a class="dropdown-item" href="#" id="SelectedDiedCountry" onclick="displayDiedCountry()">de décés</a>
                </div> -->
            </div>
            <!-- fin div catégorie -->
            <div class="dropdown">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Nombre de prix nobel
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">1</a>
                    <a class="dropdown-item" href="#">2</a>
                    <a class="dropdown-item" href="#">3</a>
                    <a class="dropdown-item" href="#">4</a>
                    <a class="dropdown-item" href="#">5</a>

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
        
        var categories = <?php echo $jsonCategories; ?>;
        var datasets = <?php echo $jsonDatasets; ?>;
        var maleData = <?php echo $jsonMaleData; ?>;
        var femaleData= <?php echo $jsonFemaleData; ?>;
        var years = <?php echo json_encode($years); ?>;
        var impactData = <?php echo json_encode($jsonImpactData); ?>; 
      
        document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('graphs').getContext('2d');
        var currentChart;
        function updateBarGraph(selectedYear) {
    var filteredData = datasets.filter(dataset => dataset.label === selectedYear);

    if (currentChart) {
        currentChart.destroy();
    }
    filteredData.forEach(dataset => {
        dataset.backgroundColor = dataset.data.map((_, index) => {
            return `hsl(${index * 50 % 360}, 100%, 70%)`;
        });
    });

    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: filteredData
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}


    // Fonction pour mettre à jour le graphique linéaire pour les données de sexe
  /*  function updateLineGraph(year) {
        if (currentChart) {
            currentChart.destroy();
        }

        var maleDataset = {
            label:`Hommes (${year}) - Prix Nobel: ${maleData[year] ? Object.values(maleData[year]).reduce((a, b) => a + b, 0) : 0}`,
            data: categories.map(category => maleData[year] && maleData[year][category] ? maleData[year][category] : 0),
            borderColor: '#42A5F5',
            fill: false
        };

        var femaleDataset = {
            label:`Femmes (${year}) - Prix Nobel: ${femaleData[year] ? Object.values(femaleData[year]).reduce((a, b) => a + b, 0) : 0}`,
            data: categories.map(category => femaleData[year] && femaleData[year][category] ? femaleData[year][category] : 0),
            borderColor: '#EC407A',
            fill: false
        };

        currentChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: categories,
                datasets: [maleDataset, femaleDataset]
            },
            options: {
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nombre de Prix Nobel'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: `Distribution des Prix Nobel par Sexe en ${year}`
                },
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            responsive: true,
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
}*/




//======= fin fonction pour afficher le gra

/*function displayImpactGraph() {
    var impactDataParsed = JSON.parse(impactData); 
    var uniqueCategories = [...new Set(impactDataParsed.map(item => item.Nom_catégorie))];
    var uniqueCountries = [...new Set(impactDataParsed.map(item => item.Pays))];
    var maxWidth = 1024;
    var baseRadius = 3;
    var bubbleData = impactDataParsed.map(item => {
        var canvasWidth = document.getElementById('graphs').offsetWidth;
        var scaleFactor = Math.min(canvasWidth / maxWidth, 1);
        return {
            x: uniqueCategories.indexOf(item.Nom_catégorie),
            y: uniqueCountries.indexOf(item.Pays), 
            r: Math.sqrt(item.TotalLaureats) * baseRadius * scaleFactor,
            gender: item.Gender,
            category: item.Nom_catégorie,
            country: item.Pays
        };
    });

    var dataset = {
        label: "Impact des Prix Nobel",
        backgroundColor: bubbleData.map(data => data.gender === 'male' ? 'rgba(0, 0, 255, 0.5)' : 'rgba(255, 20, 147, 0.5)'),
        data: bubbleData
    };

    var ctx = document.getElementById('graphs').getContext('2d');
    if (currentChart) {
        currentChart.destroy();
    }

    currentChart = new Chart(ctx, {
        type: 'bubble',
        data: {
            datasets: [dataset] 
        },
        options: {
            scales: {
                x: {
                    type: 'category',
                    labels: uniqueCategories,
                    title: {
                        display: true,
                        text: 'Catégorie'
                    }
                },
                y: {
                    type: 'category',
                    labels: uniqueCountries, 
                    title: {
                        display: true,
                        text: 'Pays'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var dataItem = context.raw;
                            return `Catégorie: ${dataItem.category}, Sexe: ${dataItem.gender}, Pays: ${dataItem.country}, Lauréats: ${Math.pow(dataItem.r / 3, 2)}`;
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Impact des Prix Nobel par Pays et Catégorie'
                }
            }
        }
    });
}*/





//==== pour le graphe circulaire sur le sexe par categories et annnée 
function updatePieGraph(year) {
    if (currentChart) {
        currentChart.destroy();
    }
    const totalByGender = { male: 0, female: 0 };
    categories.forEach(category => {
        if (maleData[year] && maleData[year][category]) {
            totalByGender.male += maleData[year][category];
        }
        if (femaleData[year] && femaleData[year][category]) {
            totalByGender.female += femaleData[year][category];
        }
    });

    var total = totalByGender.male + totalByGender.female;

    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Hommes', 'Femmes'],
            datasets: [{
                label: `Nombre de Prix Nobel par Sexe en ${year}`,
                data: [totalByGender.male, totalByGender.female],
                backgroundColor: ['#42A5F5', '#EC407A'],
                borderColor: ['darkblue', 'darkred'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    color: '#FFF',
                    formatter: (value, ctx) => {
                        let sum = ctx.dataset._meta[0].total;
                        let percentage = (value * 100 / sum).toFixed(2) + "%";
                        return percentage;
                    },
                    anchor: 'end',
                    align: 'start',
                    offset: -10
                },
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: `Distribution des Prix Nobel par Sexe en ${year}`
                }
            }
        }
    });
}

document.getElementById('CircularDiagram').addEventListener('click', function(){
    var selectedYear = document.getElementById('selectYear').value;
    updatePieGraph(selectedYear);
});

//===== fin de la fonction pour le graphe circulaire 




/*document.getElementById('Country').addEventListener('click', displayImpactGraph);*/

    document.getElementById('selectYear').addEventListener('change', function() {
        var selectedYear = this.value;
        updateBarGraph(selectedYear); 
    });

    /*document.getElementById('Gender').addEventListener('click', function() {
        var selectedYear = document.getElementById('selectYear').value;
        updateLineGraph(selectedYear); 
    });*/

//=======fonction pour afficher le gra
function grapheEnCambert(selectedYear) {
    var yearData = datasets.find(dataset => dataset.label === selectedYear);
    if (!yearData) return; 
    var ctx = document.getElementById('graphs').getContext('2d');

    if (currentChart) {
        currentChart.destroy();
    }

    var total = yearData.data.reduce((acc, value) => acc + Number(value), 0);
    currentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: categories,
            datasets: [{
                label: `Distribution des prix Nobel en ${selectedYear}`,
                data: yearData.data,
                backgroundColor: categories.map((_, index) => `hsl(${index * 360 / categories.length}, 70%, 50%)`),
                hoverOffset: 4
            }]
        },
        options: {
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                title: {
                    display: true,
                    text: `Distribution des Prix Nobel par Catégorie en ${selectedYear}`
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var label = context.label || '';
                            var value = context.raw;
                            var percentage = ((value / total) * 100).toFixed(2) + "%";
                            return `${label}: ${value} (${percentage})`;
                        }
                    }
                }
            },
            responsive: true,
        }
    });
}

document.getElementById('selectYearCam').addEventListener('click', function() {
    var selectedYear = document.getElementById('selectYear').value;
    grapheEnCambert(selectedYear);
});



    if (years.length > 0) {
        updateBarGraph(years[0]);
    }
});





  



 



 
    </script>
</body>
</html>