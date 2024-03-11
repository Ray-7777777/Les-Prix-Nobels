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
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
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
    <div class="boite">
    <div class="contenu-nobel">
    <?php
    // Récupérer l'ID de l'URL actuelle
    $id_personne = $_GET['id'];

    // Construire le lien avec le même ID
    $lien_article = "article_wikipedia.php?id=" . $id_personne;
?>

<a href="<?php echo $lien_article; ?>">Lien vers l'article Wikipedia</a>
    <?php
        $conn = getBD();

        if (!$conn) {
            die("Connexion échouée : " . implode(" ", $conn->errorInfo()));
        }

        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $id_prix_nobel = $_GET['id'];

            //requête SQL pour récupérer le prix nobel
            $sql = "SELECT * FROM prix_nobel WHERE id_prix_nobels = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id_prix_nobel, PDO::PARAM_INT);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            //requête SQL pour récupérer la categorie
            $categorie_sql = "SELECT * FROM categorie WHERE Id_catégorie = :id_category";
            $stmt_categorie = $conn->prepare($categorie_sql);
            $stmt_categorie->bindParam(':id_category', $result['id_category'], PDO::PARAM_INT);
            $stmt_categorie->execute();
    
            $categorie = $stmt_categorie->fetch(PDO::FETCH_ASSOC);

            //requête SQL pour récupérer celui qui a remporté ce prix nobel
            $nomine_sql = "SELECT * FROM nomine WHERE `Id-nominé` = :id_nomine";
$stmt_nomine = $conn->prepare($nomine_sql);
$stmt_nomine->bindParam(':id_nomine', $result['id_nominé'], PDO::PARAM_INT);
$stmt_nomine->execute();
    
            $nomine = $stmt_nomine->fetch(PDO::FETCH_ASSOC);
            
            //requête SQL pour récupérer l'organisation qui a reçu le prix nobel
            $organisation_sql = "SELECT * FROM organisation WHERE id_organisation = :id_organisation";
            $stmt_organisation = $conn->prepare($organisation_sql);
            $stmt_organisation->bindParam(':id_organisation', $result['id_organisation'], PDO::PARAM_INT);
            $stmt_organisation->execute();
    
            $organisation = $stmt_organisation->fetch(PDO::FETCH_ASSOC);


            if ($result) {
                echo "<p class='titre'>Année : " . $result['Année'] . "</p>";
                echo "<p class='titre'>Motivation : " . $result['Motivation'] . "</p>";
        
                echo "<p class='sous-titre'>Catégorie : " . $categorie["Nom_catégorie"] . "</p>";
                
                echo "<p>Personne :</p>";
                echo "<p>Nom : " . $nomine["Prénom"] ." ". $nomine["Nom"] . "</p>";
                echo "<p>Genre : " . $nomine["Gender"] . "</p>";
                echo "<p>Date de naissance : " . $nomine["Date_de_naissance"] . "</p>";
                echo "<p>Ville de naissance : " . $nomine["Born city"] . "</p>";
                echo "<p>Pays de naissance : " . $nomine["Born country"] . "</p>";
                echo "<p>Date de décès : " . $nomine["Date_de_mort"] . "</p>";
                echo "<p>Ville de décès : " . $nomine["Died city"] . "</p>";
                echo "<p>Pays de décès : " . $nomine["Died country"] . "</p>";
        
                if ($organisation["id_organisation"] != 0){
                    echo "<p class='organisation'>Organisation : " . $organisation["nom_organisation"] . "</p>";
                    echo "<p class='organisation'>Ville de l'organisation : " . $organisation["ville_organisation"] . "</p>";             
                    echo "<p class='organisation'>Pays de l'organisation : " . $organisation["pays_organisation"] . "</p>";
                } else {
                    echo "<p class='organisation'>N'appartient à aucune organisation lors de la réception de ce prix.</p>";
                }
            } else {
                echo "<p>Aucun résultat trouvé pour cet ID.</p>";
            }

            $conn = null;
        } else {
            echo "Ce prix Nobel n'est pas présent dans notre base de données";
        }
        ?>
        
    </div>
    </div>
</body>
</html>
