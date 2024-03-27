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
<div class="boite-wikipedia">
    <div class="contenu-nobel-wikipedia">
        <?php
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_personne = $_GET['id'];

            try {
                $connexion = getBD();

                $sql = "SELECT Prénom, Biographie, Photos, Nom, Date_de_naissance, `Date_de_mort`, `Born_country`, `Born_city`, `Died_country`, `Died_city`, Gender, Nom_catégorie FROM nomine, categorie WHERE `Id_nominé` = :id";

                $sqlan = "SELECT Année, Motivation, categorie.Nom_catégorie, 'Overall motivation' FROM prix_nobel INNER JOIN categorie ON prix_nobel.id_category = categorie.Id_catégorie WHERE prix_nobel.`Id_nominé` = :id";
                
                $sqlo = "SELECT nom_organisation, ville_organisation, pays_organisation FROM organisation INNER JOIN prix_nobel ON organisation.id_organisation = prix_nobel.id_organisation WHERE prix_nobel.`Id_nominé` = :id";

                $stmt = $connexion->prepare($sql);
                $stmt2 = $connexion->prepare($sqlan);
                $stmto = $connexion->prepare($sqlo);

                $stmt->bindParam(':id', $id_personne, PDO::PARAM_INT);
                $stmt2->bindParam(':id', $id_personne, PDO::PARAM_INT);
                $stmto->bindParam(':id', $id_personne, PDO::PARAM_INT);

                $stmt->execute();
                $stmt2->execute();
                $stmto->execute();

                $resultat = $stmt->fetch(PDO::FETCH_ASSOC);
                $resultat2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                $resultato = $stmto->fetch(PDO::FETCH_ASSOC);

                $photo_url = $resultat['Photos'] ? $resultat['Photos'] : 'placeholder.jpg'; 
                
                $biographie = $resultat['Biographie'];

                $formatted_biography = preg_replace_callback('/====(.*?)====/', function($matches) {
                    return "<p class='biography-heading'><strong class='biographie-texte biographie-titre3'>" . trim($matches[1]) . "</strong></p>";
                }, $biographie);
                
                $formatted_biography = preg_replace_callback('/===(.*?)===/', function($matches) {
                    return "<br><p class='biography-heading'><strong class='biographie-texte biographie-titre2'>" . trim($matches[1]) . "</strong></p>";
                }, $formatted_biography);

                $formatted_biography = preg_replace_callback('/==(.*?)==/', function($matches) {
                    return "<br><p class='biography-heading'><strong class='biographie-texte biographie-titre1'>" . trim($matches[1]) . "</strong></p>";
                }, $formatted_biography);

                // Récupération des titres et des paragraphes
                $matches = [];
                preg_match_all('/===[^=]+===\s*(.*?)(?:(?===)|(?===))/s', $formatted_biography, $matches);

                // Variable pour la numérotation
                $numeration = 1;

                // Parcourir chaque titre et paragraphe pour ajouter des identifiants et la numérotation
                foreach ($matches[0] as $index => $match) {
                    $content = $matches[1][$index];
                    // Appliquer le formatage au paragraphe
                    $content = preg_replace('/\n/', '<br>', $content);
                    // Ajouter la numérotation aux titres biographie-titre1
                    if (strpos($match, 'biographie-titre1') !== false) {
                        $formatted_biography = str_replace($match, "<br><p class='biography-heading'><strong class='biographie-texte'>$numeration. " . trim($matches[1][$index]) . "</strong></p>", $formatted_biography);
                        $numeration++;
                    } else {
                        $formatted_biography = str_replace($match, "<br><p class='biography-heading'><strong class='biographie-texte'>" . trim($matches[1][$index]) . "</strong></p>", $formatted_biography);
                    }
                }
                

                // Affichage de la biographie
                echo "<div style='margin-right:2%;margin-left:2%;'>"; 
                echo "<div style='float: right; margin-left: 10px; width: 300px;'>"; 
                echo "<div class='image-container' style='background-color: white;box-shadow: 0 0 10px rgba(1, 1, 1, 0.4);border-radius: 25px;border: 1.5px solid black; text-align: center;padding-top:2%;margin-top:1.5%;margin-right:1.5%;'>"; 
                echo "<img src='$photo_url' alt='Photo' style='max-width: 300px; max-height: 200px; margin: auto;border:1.5px solid black;'>"; 
                
                // Affichage des informations personnelles
                
          
                if ($resultat['Prénom'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:2%'><strong>Prénom :</strong> {$resultat['Prénom']}</p>";
                }      
                
                if ($resultat['Nom'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Nom :</strong> {$resultat['Nom']}</p>";
                }

                if ($resultat['Date_de_naissance'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Date de naissance :</strong> {$resultat['Date_de_naissance']}</p>";
                }

                if ($resultat['Date_de_mort'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Date de mort :</strong> {$resultat['Date_de_mort']}</p>";
                }

                if ($resultat['Born_country'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Pays de naissance :</strong> {$resultat['Born_country']}</p>";
                }

                if ($resultat['Born_city'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Ville de naissance :</strong> {$resultat['Born_city']}</p>";
                }

                if ($resultat['Died_country'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Pays de mort :</strong> {$resultat['Died_country']}</p>";
                }

                if ($resultat['Died_city'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Ville de mort :</strong> {$resultat['Died_city']}</p>";
                }

                if ($resultat['Gender'] !== "NULL") {
                    echo "<p style='font-weight:normal;text-align: left;padding-left:5%;margin-top:-6%'><strong>Genre :</strong> {$resultat['Gender']}</p>";
                }
                
<<<<<<< Updated upstream
                echo "<p style='font-weight:normal;padding-bottom:10px;padding-top:20px;text-align: center;padding-left:5%;margin-top:-6%;text-decoration:underline;'><strong>À propos du prix Nobel :</p>";
                
                if ($resultato['nom_organisation'] !== "pas d’organisation") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Organisation :</strong> {$resultato['nom_organisation']}</p>";
                }
                
                if ($resultato['pays_organisation'] !== "pas d’organisation") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Pays de l'organisation :</strong> {$resultato['pays_organisation']}</p>";
                }
                
                if ($resultato['ville_organisation'] !== "pas d’organisation") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Ville de l'organisation :</strong> {$resultato['ville_organisation']}</p>";
                }
                
                if ($resultat2['Nom_catégorie'] !== "NULL") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Catégorie :</strong> {$resultat2['Nom_catégorie']}</p>";
                }
                
                if ($resultat2['Année'] !== "NULL") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Année :</strong> {$resultat2['Année']}</p>";
                }
                
                if ($resultat2['Motivation'] !== "NULL") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Motivation :</strong> {$resultat2['Motivation']}</p>";
                }
                
                
                
           
                
               
           
                
=======
                if ($resultat['Nom_catégorie'] !== "NULL") {
                    echo "<p style='text-align: left;padding-left:5%;margin-top:-6%'><strong>Catégorie prix nobel :</strong> {$resultat['Nom_catégorie']}</p>";
                }
                
                
>>>>>>> Stashed changes

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

<script>
    // Attendre que le document soit complètement chargé
    document.addEventListener("DOMContentLoaded", function() {
        // Sélectionner tous les éléments ayant les classes biographie-titre1, biographie-titre2 et biographie-titre3
        var titles1 = document.querySelectorAll('.biographie-titre1');
        var titles2 = document.querySelectorAll('.biographie-titre2');
        var titles3 = document.querySelectorAll('.biographie-titre3');

        // Fonction pour ajouter un titre à la liste des titres
        function addTitleToList(title, list) {
            // Créer un élément de liste
            var listItem = document.createElement('li');
            // Créer un lien avec le titre comme texte et l'ancre correspondante comme href
            var link = document.createElement('a');
            link.textContent = title.textContent.trim();
            link.href = '#' + title.id;
            // Ajouter le lien à l'élément de liste
            listItem.appendChild(link);
            // Ajouter l'élément de liste à la liste
            list.appendChild(listItem);
        }

        // Sélectionner le conteneur de menu des titres
        var titlesMenu = document.querySelector('.menu-titres ul');

        // Ajouter chaque titre à la liste des titres
        titles1.forEach(function(title) {
            addTitleToList(title, titlesMenu);
        });

        titles2.forEach(function(title) {
            addTitleToList(title, titlesMenu);
        });

        titles3.forEach(function(title) {
            addTitleToList(title, titlesMenu);
        });
    });
</script>
	

</body>




</html>
