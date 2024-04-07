<?php
include("connexion_base_donnee.php");
// Récupération des années 
$queryYears = "SELECT DISTINCT Année FROM prix_nobel   ORDER BY Année ASC" ;
$resultYears = $mysqli->query($queryYears);
$years = array();

while ($row = $resultYears->fetch_assoc()) {
    $years[] = $row['Année'];
}



// ==========================================
// ====Implication des organisations
$organisation= "SELECT nom_organisation AS Organisation, COUNT(*) AS NombreLauréats
FROM prix_nobel
JOIN organisation ON prix_nobel.id_organisation = organisation.id_organisation
GROUP BY nom_organisation
HAVING COUNT(*) >= 10
ORDER BY COUNT(*) DESC";
$requeteOrganisation =  $mysqli->query($organisation);
$var_Array_Organisation=array();
while ($row=$requeteOrganisation->fetch_assoc()){
    $var_Array_Organisation[]=$row;
}

// =========================================================
// ===Implications des pays des organisations qui on remporté plus de 5 prix nobel: pays d'origine 
$paysOrganisations = "SELECT pays_organisation AS Pays, COUNT(*) AS NombreLaureats
                FROM organisation
                JOIN prix_nobel ON prix_nobel.id_organisation = organisation.id_organisation
                GROUP BY pays_organisation
                HAVING COUNT(*) > 5
                ORDER BY COUNT(*) DESC";

$requetePaysOrganisations = $mysqli->query($paysOrganisations);
$var_Array_PaysOrg = array();

while ($row = $requetePaysOrganisations->fetch_assoc()) {
    $var_Array_PaysOrg[] = $row;
}
$paysOrganisationsJSON = json_encode($var_Array_PaysOrg);


// =========================================================
// ===nombre de lauréats et evolution au fil des années dans différentes catégories(historique) tous les 20 ans
$queryHistorique = "SELECT 
CONCAT(FLOOR(p.Année / 20) * 20, ' - ', FLOOR(p.Année / 20) * 20 + 19) AS Intervalle,
c.Nom_catégorie,
COUNT(*) AS nbLauréats
FROM 
prix_nobel p
JOIN 
nomine n ON p.id_nominé = n.`Id-nominé`
JOIN 
categorie c ON p.id_category = c.Id_catégorie
GROUP BY 
FLOOR(p.Année / 20),
c.Nom_catégorie
ORDER BY 
FLOOR(p.Année / 20),
c.Nom_catégorie;
";

$requete = $mysqli->query($queryHistorique);

$donnees = array();

while ($row = $requete->fetch_assoc()) {
    $donnees[] = $row;
}
$donneesJSON_Historique = json_encode($donnees);



//-------------------------------------------------------------------------------------





///========================================= nv query 
$queryCam = "SELECT Gender, COUNT(*) AS nombre_prix_nobel
          FROM prix_nobel pn
          JOIN nomine n ON pn.id_nominé = n.`Id-nominé`
          WHERE Gender IN ('female', 'male')
          GROUP BY Gender";
          //declaration de variable en php 

$queryCamExecute = $mysqli->query($queryCam);
$queryCamArray = array();
while ($row = $queryCamExecute->fetch_assoc()){
    $queryCamArray[] = $row;
}

// récupération des années par decennies
$queryDecennies = "SELECT DISTINCT CONCAT(FLOOR(Année / 10) * 10, ' - ', FLOOR(Année / 10) * 10 + 9) AS DecennieStart FROM prix_nobel ORDER BY DecennieStart ASC";
$resultDecennies = $mysqli->query($queryDecennies);
$decennies = array(); // le tableau il est vide 

while ($row = $resultDecennies->fetch_assoc()) {
    $decennies[] = $row['DecennieStart'];
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

//  requete pour recuperation des pays de naissance à partir de la base de donnée
$birthCountryQuery = $mysqli->query($birthCountry);
$birthCountryArrResponseQuery = array();
while ($row = $birthCountryQuery->fetch_assoc()){
    $birthCountryArrResponseQuery[] = $row;
}

//  requete pour recuperation des pays de décés à partir de la base de donnée
$deathCountryQuery = $mysqli->query($deathCountry);
$deathCountryArrResponseQuery = array();
while ($row = $deathCountryQuery->fetch_assoc()){
    $deathCountryArrResponseQuery[] = $row;
}



// affichage des graphiques
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
                <a class="btn btn-secondary dropdown-toggle " id="Gender" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Axe X </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Catégorie</a>
                    <a class="dropdown-item" href="#" id="pays">Pays de naissance</a>
                    <a class="dropdown-item" href="#" id="historique">Historique</a>
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
                    <p class="selected-graphe">Le graphe sélectionné</p>
                    <canvas id="graphs"></canvas>
                </div>
            </ul>
        </div>
        <!-- fin du contenu du canvas -->
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>


     
        
        // Recuperation des données JSON encodées depuis PHP
        var donneesHistorique = <?php echo $donneesJSON_Historique; ?>;

        // Utilisation les données dans votre script JavaScript
        console.log(donneesHistorique);

        // conversion en JSON pour l'envoyer à JavaScript : pays d'origine des organisations
       
        var organisation = <?php echo json_encode($var_Array_Organisation); ?>;
        // 
        var categories = <?php echo $jsonCategories; ?>;
        var datasets = <?php echo $jsonDatasets; ?>;
        var maleData = <?php echo $jsonMaleData; ?>;
        var femaleData= <?php echo $jsonFemaleData; ?>;
        var years = <?php echo json_encode($years); ?>;
        
        // recuperation des annees par decennies 
        var decennies = <?php echo json_encode($decennies); ?>;
        var impactData = <?php echo json_encode($jsonImpactData); ?>;
        var queryCam =  <?php echo json_encode($queryCamArray); ?>; 
        var donneesPaysOrganisation = <?php echo ($paysOrganisationsJSON); ?>;
        
        var currentChart;
      
        document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('graphs').getContext('2d');
      
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



///==========================
function CouleurAleatoire (){
    var rouge = Math.floor(Math.random() * 255);
    var vert = Math.floor(Math.random() * 255);
    var bleu = Math.floor(Math.random() * 255);
    return 'rgba(' + rouge + ',' + vert +',' + bleu + ', 0.5)';
}

//-----------------------------------------------------------------
function grapheLineaire() {
    var categories = [...new Set(donneesHistorique.map(item => item.Nom_catégorie))];
    var intervalles = [...new Set(donneesHistorique.map(item => item.Intervalle))].sort();

    var datasets = categories.map(catégorie => {
    var dataPourCategorie = donneesHistorique.filter(item => item.Nom_catégorie === catégorie);
    var data = intervalles.map(intervalle => {
        var item = dataPourCategorie.find(item => item.Intervalle === intervalle);
        return item ? item.nbLauréats : 0;
    });

    return {
        label: catégorie,
        data: data,
        fill: false,
  
    };
});
    if (currentChart) {
        currentChart.destroy();
    } 

  var ctx = document.getElementById('graphs').getContext('2d');
var monGraphique = new Chart(ctx, {
    type: 'line', 
    data: {
        labels: intervalles,
        datasets: datasets
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        },
        plugins: {
            title: {
                display: true,
                text: 'Nombre de lauréats par catégorie et intervalle d\'années'
            }
        }
    }
});

}

document.getElementById('historique').addEventListener('click', function() {
    grapheLineaire();
});


// --------------------------------------------------------------------------







function grapheEnBarrehorizontal(){
    var nomOrganisation = organisation.map(function(o){return o.Organisation});
    var nombreLaureats = organisation.map(function(o){return o.NombreLauréats});
    var barColor = organisation.map(function() { return CouleurAleatoire(); });

    if (currentChart) {
        currentChart.destroy();
   } 
   var ctx = document.getElementById('graphs').getContext('2d');
   currentChart = new Chart(ctx, {
    type: 'bar',
    data:{
        labels: nomOrganisation,
        datasets:[{
            backgroundColor: barColor,
            data: nombreLaureats,
        }]
    },
    options:{
        indexAxis: 'y',
        plugins:{
            title:{
                display : true,
                text : 'Organisation ayant au moins 10 prix nobels'
            }
        },
        scales :{
            x: {
                beginAtZero : true,
                ticks:{
                    stepSize: 10
                }
            }
        }
    }


   });

}
document.getElementById('organisation').addEventListener('click', function() {
    grapheEnBarrehorizontal();
});



function graphEnBarreOrg (){
    var orgPays = donneesPaysOrganisation.map(function(i) { return i.Pays; });
    var orgNombreLaureats = donneesPaysOrganisation.map(function(i) { return i.NombreLaureats; });
    var barColor = donneesPaysOrganisation.map(function() { return CouleurAleatoire(); });
    
    if (currentChart) {
        currentChart.destroy();
   } 
   var ctx = document.getElementById('graphs').getContext('2d');

   currentChart = new Chart(ctx, {
    type: 'bar',
    data:{
        labels: orgPays,
        datasets:[{
            backgroundColor: barColor,
            data: orgNombreLaureats,
        }]
    },
    options:{
        indexAxis: 'y',
        plugins:{
            title:{
                display : true,
                text : 'Pays d\'origine des organisations ayant au moins 5 lauréats '
            }
        },
        scales :{
            x: {
                beginAtZero : true,
                ticks:{
                    stepSize: 5
                }
            }
        }
    }
   });
}
document.getElementById('pays').addEventListener('click', function() {
    graphEnBarreOrg();
});






//le graphe circulaire sur le sexe par categories et annnée 
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
// depend plus de l'année 
function grapheEnCambert(selectYear) {
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

function grapheEnCam2(){
    if (currentChart) {
        currentChart.destroy();
    }
    var ctx = document.getElementById('graphs').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: queryCam.map(item => item.Gender),
            datasets: [{
                label: `Distribution des prix Nobel en`,
                
                data: queryCam.map(item => item.nombre_prix_nobel),
               //backgroundColor: queryCam.Gender.map((_, index) => `hsl(${index * 360 / categories.length}, 70%, 50%)`),
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
                    display: false,
                    text: `Distribution des Prix Nobel par Catégorie en`
                }
            },
            responsive: true,
        }
    });

}
document.getElementById('SEXE').addEventListener('click', function() {
    grapheEnCam2()
});


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