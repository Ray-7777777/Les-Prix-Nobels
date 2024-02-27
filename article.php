<!DOCTYPE html>
<html>
<head>
	<title>Recherche</title>
	<script type="text/javascript" src="../jquery-3.7.1.js"></script>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<link rel="stylesheet" href="style.css?v=1.1" type="text/css" media="screen" />
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
    <?php
        include "bd.php";
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
            $nomine_sql = "SELECT * FROM nomine WHERE Id_nominé = :id_nomine";
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
                // affiche les information du prix Nobel
                echo "<p>Année : " . $result['Année'] . "</p>";
                echo "<p>Motivation : " . $result['Motivation'] . "</p>";

                echo "<p>Catégorie : " . $categorie["Nom_catégorie"] . "</p>";
                
                echo "people:";
                echo "<p> " . $nomine["Prénom"] ." ". $nomine["Nom"] . "</p>";
                echo "<p>Gender: " . $nomine["Gender"] . "</p>";
                echo "<p>Date of birth: " . $nomine["Date_de_naissance"] . "</p>";
                echo "<p>Born City: " . $nomine["Born city"] . "</p>";
                echo "<p>Born Country: " . $nomine["Born country"] . "</p>";
                echo "<p>Date of death: " . $nomine["Date_de_mort"] . "</p>";
                echo "<p>Died City: " . $nomine["Died city"] . "</p>";
                echo "<p>Died Country: " . $nomine["Died country"] . "</p>";

                echo "organisation:";
                echo "<p>Organisation : " . $organisation["nom_organisation"] . "</p>";
                echo "<p>Organisation's city : " . $organisation["ville_organisation"] . "</p>";             
                echo "<p>Organisation's Country : " . $organisation["pays_organisation"] . "</p>";
            } else {
                echo "Aucun résultat trouvé pour cet ID.";
            }

            $conn = null;
        } else {
            echo "Ce prix Nobel n'est pas présent dans notre base de données";
        }
        ?>

    </div>
</body>
</html>
