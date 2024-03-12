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
                <div class="collapse navbar-collapse" id="navbarSupportedContent" style="background-color:#FFFDF1;">
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

    <div class="boite-wikipedia">
        <div class="contenu-nobel-wikipedia">
            <?php
            if (isset($_GET['id']) && is_numeric($_GET['id'])) {
                $id_personne = $_GET['id'];

                try {
                    $connexion = getBD();

                    $sql = "SELECT Prénom, Biographie, Photos, Nom, Date_de_naissance, `Date_de_mort`, `Born country`, `Born city`, `Died country`, `Died city`, Gender FROM nomine WHERE `Id-nominé` = :id";

                    $stmt = $connexion->prepare($sql);
                    $stmt->bindParam(':id', $id_personne, PDO::PARAM_INT);
                    $stmt->execute();
                    $resultat = $stmt->fetch(PDO::FETCH_ASSOC);

                    $photo_url = $resultat['Photos'] ? $resultat['Photos'] : 'placeholder.jpg'; 
                    
                    $biographie = $resultat['Biographie'];

                    $formatted_biography = preg_replace_callback('/====(.*?)====/', function($matches) {
                        return "<br><p class='biography-heading'><strong class='biographie-texte biographie-titre1'>" . trim($matches[1]) . "</strong></p><br>";
                    }, $biographie);
                    
                    $formatted_biography = preg_replace_callback('/===\s*(.*?)\s*===/', function($matches) {
                        return "<br><p class='biography-heading'><strong class='biographie-texte biographie-titre2'>" . trim($matches[1]) . "</strong></p><br>";
                    }, $formatted_biography);

                    $formatted_biography = preg_replace_callback('/==\s*(.*?)\s*==/', function($matches) {
                        return "<br><p class='biography-heading'><strong class='biographie-texte biographie-titre3'>" . trim($matches[1]) . "</strong></p><br>";
                    }, $formatted_biography);

                    echo "<div style='margin-right:2%;margin-left:2%;'>"; 
                    echo "<div style='float: right; margin-left: 10px; width: 300px;'>"; 
                    echo "<div class='image-container' style='border: 1px solid black; text-align: center;padding-top:2%;margin-top:4%;margin-right:5%;'>"; 
                    echo "<img src='$photo_url' alt='Photo de la personne' style='max-width: 300px; max-height: 200px; margin: auto;'>"; 
                    
                    if ($resultat['Prénom']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:2%'><strong>Prénom :</strong> {$resultat['Prénom']}</p>";
                    }

                    if ($resultat['Nom']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Nom :</strong> {$resultat['Nom']}</p>";
                    }

                    if ($resultat['Date_de_naissance']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Date de naissance :</strong> {$resultat['Date_de_naissance']}</p>";
                    }

                    if ($resultat['Date_de_mort']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Date de mort :</strong> {$resultat['Date_de_mort']}</p>";
                    }

                    if ($resultat['Born country']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Pays de naissance :</strong> {$resultat['Born country']}</p>";
                    }

                    if ($resultat['Born city']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Ville de naissance :</strong> {$resultat['Born city']}</p>";
                    }

                    if ($resultat['Died country']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Pays de mort :</strong> {$resultat['Died country']}</p>";
                    }

                    if ($resultat['Died city']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Ville de mort :</strong> {$resultat['Died city']}</p>";
                    }

                    if ($resultat['Gender']) {
                        echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Genre :</strong> {$resultat['Gender']}</p>";
                    }

                    echo "</div>"; 
                    echo "</div>"; 
                    echo "<div style='margin-top: 10px; min-width: 75%;'>"; 
                    echo "<p class='biography-text'>";
                    echo $formatted_biography; 
                    echo "</p>"; 
                    echo "</div>"; 
                    echo "</div>";
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            }
            ?>

        </div>
    </div>
</body>

</html>
