<?php
session_start();
require_once 'connexion_bd.php';
$est_connecte = isset($_SESSION['user_id']);

// Récupérer la connexion à la base de données
$connexion = getBD();

// Récupérer les données sur le sexe des lauréats du prix Nobel depuis la base de données
$sql = "SELECT COUNT(*) AS nombre_hommes FROM nomine WHERE Gender = 'male'";
$stmt = $connexion->query($sql);
$resultat = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre_hommes = $resultat['nombre_hommes'];

$sql = "SELECT COUNT(*) AS nombre_femmes FROM nomine WHERE Gender = 'female'";
$stmt = $connexion->query($sql);
$resultat = $stmt->fetch(PDO::FETCH_ASSOC);
$nombre_femmes = $resultat['nombre_femmes'];

$sqlNationalites = "SELECT Born_Country AS Nationalite, COUNT(*) AS Nombre FROM nomine GROUP BY Born_Country ORDER BY Nombre DESC LIMIT 6";
$stmtNationalites = $connexion->query($sqlNationalites);
$nationalites = $stmtNationalites->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les données sur si le prix Nobel est remporté par une organisation ou une personne
$sqlTypeLaureat = "SELECT COUNT(*) AS nombre_organisations FROM prix_nobel p JOIN organisation o ON p.id_organisation = o.id_organisation WHERE o.id_organisation = 0";

$stmtTypeLaureat = $connexion->query($sqlTypeLaureat);
$resultatTypeLaureat = $stmtTypeLaureat->fetch(PDO::FETCH_ASSOC);
$nombre_organisations = $resultatTypeLaureat['nombre_organisations'];
$nombre_personnes = $nombre_hommes + $nombre_femmes - $nombre_organisations;
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
    <div class="boite_graphique">
	 <p id="lien-graph">Si vous souhaitez faire votre propre graphique : <a id="lien-graphe" href="page_graphique copy.php">cliquez ici</a></p>

   
    
    <h2 class="titre_graphique_prefait">Exemples de graphiques</h2>
    <div class="row">
        <div class="col-lg-4">
            <div class="graphique_prefait">
                <canvas id="graphiqueSexe" width="350" height="350"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="graphique_prefait">
                <canvas id="graphiqueNationalites1" width="350" height="350"></canvas>
            </div>
        </div>
        <div class="col-lg-4">
    <div class="graphique_prefait">
        <canvas id="graphiqueType" width="350" height="350"></canvas>
    </div>
</div>
    </div>
</div>

    <script>
        // Récupérer les données des sexes depuis PHP
        var nombreHommes = <?php echo $nombre_hommes; ?>;
        var nombreFemmes = <?php echo $nombre_femmes; ?>;

        var ctxSexe = document.getElementById('graphiqueSexe').getContext('2d');
        var graphiqueSexe = new Chart(ctxSexe, {
            type: 'pie',
            data: {
                labels: ['Hommes', 'Femmes'],
                datasets: [{
                    label: 'Répartition par sexe',
                    data: [nombreHommes, nombreFemmes],
                    backgroundColor: [
                        'rgb(167, 217, 255)',
                        'rgb(255, 189, 245)'
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Répartition des lauréats du prix Nobel par sexe',
                        color: 'black', 
                        font: {
                            size: 16, 
                            weight: 'normal', 
                            family: '"PT Sans", sans-serif', 
                            style: 'normal' 
                        },
                        shadow: {
                            color: 'rgba(0, 0, 0, 0.2)',
                            blur: 2,
                            offsetX: 2,
                            offsetY: 2
                        }
                    }
                }
            }
        });

        var nationalites = <?php echo json_encode($nationalites); ?>;

        var ctxNationalites1 = document.getElementById('graphiqueNationalites1').getContext('2d');
        var graphiqueNationalites1 = new Chart(ctxNationalites1, {
            type: 'bar',
            data: {
                labels: nationalites.map(function(item) { return item.Nationalite; }).slice(0, 7),
                datasets: [{
                    label: 'Top 6 des nationalités',
                    data: nationalites.map(function(item) { return item.Nombre; }).slice(0, 7),
                    backgroundColor: 'rgb(255, 171, 177)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Top 6 des nationalités des lauréats du prix Nobel',
                        color: 'black',
                        font: {
                            size: 16, 
                            weight: 'normal', 
                            family: '"PT Sans", sans-serif', 
                            style: 'normal' 
                        },
                        shadow: {
                            color: 'rgba(0, 0, 0, 0.2)',
                            blur: 2,
                            offsetX: 2,
                            offsetY: 2
                        }
                    }
                }
            }
        });
        
var nombreOrganisations = <?php echo $nombre_organisations; ?>;
var nombrePersonnes = <?php echo $nombre_personnes; ?>;

       // Créer le graphique en barres pour la répartition par type de lauréat
var ctxType = document.getElementById('graphiqueType').getContext('2d');
var graphiqueType = new Chart(ctxType, {
    type: 'pie',
    data: {
        labels: ['Organisations', 'Personnes'],
        datasets: [{
            label: 'Répartition par type de lauréat',
            data: [nombreOrganisations, nombrePersonnes],
            backgroundColor: [
                'rgb(123, 252, 192)',
                'rgb(252, 209, 123)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            title: {
                display: true,
                text: 'Répartition des lauréats du prix Nobel par type',
                color: 'black',
                font: {
                    size: 16,
                    weight: 'normal',
                    family: '"PT Sans", sans-serif',
                    style: 'normal'
                },
                shadow: {
                    color: 'rgba(0, 0, 0, 0.2)',
                    blur: 2,
                    offsetX: 2,
                    offsetY: 2
                }
            }
        }
    }
});
        
</script>
</body>
</html> 
